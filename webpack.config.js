const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const { WebpackManifestPlugin } = require('webpack-manifest-plugin')
const WebpackNotifierPlugin = require('webpack-notifier')
const webpack = require('webpack')
const fs = require('fs')
const glob = require('glob')

const banner = `
/*
Theme Name: Inc Digital Theme
Theme URI: https://github.com/kleytoncaires/wordpress-webpack-theme
Author: Kleyton Caires
Description: A WordPress theme built with Webpack.
Version: 2024.10
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-theme
*/
`

// Plugin customizado para limpar arquivos com hash antigos
class CleanHashedFilesPlugin {
    apply(compiler) {
        compiler.hooks.beforeRun.tap('CleanHashedFilesPlugin', () => {
            this.cleanAllHashedFiles(compiler.options.output.path)
        })

        compiler.hooks.watchRun.tap('CleanHashedFilesPlugin', (compilation) => {
            this.cleanSelectiveHashedFiles(
                compiler.options.output.path,
                compilation
            )
        })
    }

    cleanAllHashedFiles(outputPath) {
        try {
            // Limpar todos os arquivos com hash no build inicial
            const hashedFiles = glob.sync(
                '*.[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9].{css,js,map}',
                { cwd: outputPath }
            )
            hashedFiles.forEach((file) => {
                const filePath = path.join(outputPath, file)
                if (fs.existsSync(filePath)) {
                    fs.unlinkSync(filePath)
                }
            })
        } catch (error) {
            console.warn('Erro ao limpar arquivos com hash:', error.message)
        }
    }

    cleanSelectiveHashedFiles(outputPath, compilation) {
        try {
            const changedFiles = compilation.modifiedFiles || new Set()
            const shouldCleanCSS = Array.from(changedFiles).some(
                (file) => file.endsWith('.scss') || file.endsWith('.css')
            )
            const shouldCleanJS = Array.from(changedFiles).some((file) =>
                file.endsWith('.js')
            )

            if (shouldCleanCSS) {
                // Limpar apenas arquivos CSS e seus maps
                const cssFiles = glob.sync(
                    'style.[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9].{css,css.map}',
                    { cwd: outputPath }
                )
                cssFiles.forEach((file) => {
                    const filePath = path.join(outputPath, file)
                    if (fs.existsSync(filePath)) {
                        fs.unlinkSync(filePath)
                    }
                })
            }

            if (shouldCleanJS) {
                // Limpar apenas arquivos JS e seus maps
                const jsFiles = glob.sync(
                    'scripts.[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9].{js,js.map}',
                    { cwd: outputPath }
                )
                jsFiles.forEach((file) => {
                    const filePath = path.join(outputPath, file)
                    if (fs.existsSync(filePath)) {
                        fs.unlinkSync(filePath)
                    }
                })

                // Também limpar o style.js da pasta .temp
                const styleJsFiles = glob.sync(
                    'style.[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9].{js,js.map}',
                    { cwd: path.join(outputPath, '.temp') }
                )
                styleJsFiles.forEach((file) => {
                    const filePath = path.join(outputPath, '.temp', file)
                    if (fs.existsSync(filePath)) {
                        fs.unlinkSync(filePath)
                    }
                })
            }
        } catch (error) {
            console.warn(
                'Erro ao limpar arquivos seletivamente:',
                error.message
            )
        }
    }
}

// Plugin customizado para criar style.css limpo
class CleanStyleCssPlugin {
    apply(compiler) {
        compiler.hooks.afterEmit.tap('CleanStyleCssPlugin', () => {
            const styleCssPath = path.resolve(
                compiler.options.output.path,
                'style.css'
            )
            fs.writeFileSync(styleCssPath, banner)
        })
    }
}

module.exports = (env, argv) => {
    const isProduction =
        argv.mode === 'production' || process.env.NODE_ENV === 'production'

    return {
        mode: isProduction ? 'production' : 'development',
        devtool: 'source-map',

        entry: {
            style: './assets/css/style.scss',
            scripts: './assets/js/scripts.js',
        },

        output: {
            path: path.resolve(__dirname, './'),
            filename: (pathData) => {
                // Mover apenas style.js para .temp
                return pathData.chunk.name === 'style'
                    ? '.temp/[name].[contenthash:8].js'
                    : '[name].[contenthash:8].js'
            },
        },

        module: {
            rules: [
                {
                    test: /\.scss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: {
                                url: false,
                                sourceMap: true,
                            },
                        },
                        {
                            loader: 'sass-loader',
                            options: {
                                implementation: require('sass'),
                                sassOptions: {
                                    quietDeps: true,
                                    verboseLimit: 0,
                                },
                                sourceMap: true,
                            },
                        },
                    ],
                },
            ],
        },

        plugins: [
            new MiniCssExtractPlugin({
                filename: 'style.[contenthash:8].css',
            }),

            new webpack.BannerPlugin({
                banner,
                raw: true,
                test: /\.css$/,
            }),

            new WebpackNotifierPlugin({
                title: '❌ SASS Error',
                sound: 'Submarine',
                wait: true,
                onlyOnError: true,
                skipFirstNotification: true,
            }),

            new WebpackManifestPlugin({
                fileName: 'asset-manifest.json',
                publicPath: '',
                filter: (file) => {
                    return (
                        (file.path.endsWith('.css') ||
                            file.path.endsWith('.js')) &&
                        !file.path.endsWith('.map')
                    )
                },
                generate: (seed, files) => {
                    const manifestFiles = files.reduce((manifest, file) => {
                        manifest[file.path] = file.path
                        return manifest
                    }, seed)
                    return { files: manifestFiles }
                },
            }),

            new CleanHashedFilesPlugin(),
            new CleanStyleCssPlugin(),
        ],

        // Watch mode configuration
        watchOptions: {
            ignored: /node_modules/,
            poll: 1000,
        },
    }
}
