import esbuild from 'esbuild';

esbuild.build({
    entryPoints: ['resources/js/documentation.js'],
    outfile: 'resources/dist/filament-documentation.js',
    bundle: true,
    minify: true,
    format: 'iife',
    target: ['es2020'],
}).catch(() => process.exit(1));
