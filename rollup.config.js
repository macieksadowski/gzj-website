// Plugins
import { terser } from 'rollup-plugin-terser';
import { nodeResolve } from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import pkg from './package.json';


// Configs
var configs = {
	name: 'BuildToolsCookbook',
	files: ['jquery-global.js', 'main.js', 'public.js', 'dashboard.js', 'contracts.js', 'edit-contracts-form.js', 'events.js', 'finances.js'],
	formats: ['iife', 'es', 'amd', 'cjs'],
	default: 'iife',
	pathIn: 'resources/js',
	pathOut: 'public_html/js',
	minify: true,
	sourceMap: false,
};

// Banner
var banner = `/*! ${configs.name ? configs.name : pkg.name} v${pkg.version} | (c) ${new Date().getFullYear()} ${pkg.author.name} | ${pkg.license} License | ${pkg.repository.url} */`;

var createOutput = function (filename, minify) {
	return configs.formats.map(function (format) {
		var output = {
			file: `${configs.pathOut}/${filename}${format === configs.default ? '' : `.${format}`}${minify ? '.min' : ''}.js`,
			format: format,
			banner: banner,
		};
		if (format === 'iife') {
			output.name = configs.name ? configs.name : pkg.name;
		}
		if (minify) {
			output.plugins = [terser()];
		}

		output.sourcemap = configs.sourceMap

		return output;
	});
};

/**
 * Create output formats
 * @param  {String} filename The filename
 * @return {Array}           The outputs array
 */
var createOutputs = function (filename) {

	// Create base outputs
	var outputs = createOutput(filename);

	// If not minifying, return outputs
	if (!configs.minify) return outputs;

	// Otherwise, ceate second set of outputs
	var outputsMin = createOutput(filename, true);

	// Merge and return the two arrays
	return outputs.concat(outputsMin);

};

/**
 * Create export object
 * @return {Array} The export object
 */
var createExport = function (file) {
	return configs.files.map(function (file) {
		var filename = file.replace('.js', '');
		return {
			input: `${configs.pathIn}/${file}`,
			output: createOutputs(filename),
			plugins: [
				nodeResolve(),
				commonjs(),
			]
		};
	});
};

export default createExport();