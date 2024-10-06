import esbuild from 'esbuild'

const isDev = process.argv.includes('--dev')

async function compile(options) {
    const context = await esbuild.context(options)

    if (isDev) {
        await context.watch()
    } else {
        await context.rebuild()
        await context.dispose()
    }
}

const defaultOptions = {
    define: {
        'process.env.NODE_ENV': isDev ? `'development'` : `'production'`,
    },
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    sourcemap: isDev ? 'inline' : false,
    sourcesContent: isDev,
    treeShaking: true,
    target: ['es2020'],
    minify: !isDev,
    plugins: [{
        name: 'watchPlugin',
        setup: function (build) {
            build.onStart(() => {
                console.log(`Build started at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
            })

            build.onEnd((result) => {
                if (result.errors.length > 0) {
                    console.log(`Build failed at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`, result.errors)
                } else {
                    console.log(`Build finished at ${new Date(Date.now()).toLocaleTimeString()}: ${build.initialOptions.outfile}`)
                }
            })
        }
    }],
}

const builds = [
    {
        entryPoints: ['./resources/js/index.js'],
        outfile: './resources/dist/filament-advanced.js',
    },
    {
        entryPoints: ['./resources/js/fa-combobox.js'],
        outfile: './resources/dist/fa-combobox.js',
    },
];

async function buildAll() {
    try {
        await Promise.all(builds.map(buildOptions => 
            compile({ ...defaultOptions, ...buildOptions })
        ));
        console.log('All builds completed successfully');
    } catch (error) {
        console.error('Build failed:', error);
        process.exit(1);
    }
}

buildAll();

// compile({
//     ...defaultOptions,
//     entryPoints: ['./resources/js/index.js'],
//     outfile: './resources/dist/filament-advanced.js',
// })
