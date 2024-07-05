const ncp = require('ncp');
const rimraf = require('rimraf');
const path = require('path');

const source = process.cwd();
const destination = 'deploy';

const excludedFiles = [
	destination,
	'src',
	'node_modules',
	'.git',
	'.bin',
	'.babelrc',
	'.gitignore',
	'package.json',
	'package-lock.json',
	'.map',
	'js.pot',
	'translation-js.php',
	'info.md',
];

const filterFunction = (file) => {
	const relativePath = path.relative(source, file);
	const fileName = path.basename(file);
	const fileExtension = path.extname(file);
	const isExcluded =
		excludedFiles.includes(fileName) || excludedFiles.includes(fileExtension);
	if (isExcluded) {
		console.log(`Excluded: ${fileName}`);
	}
	return !isExcluded;
};

// Delete the destination directory
rimraf(destination, (error) => {
	if (error) {
		console.error('Error occurred:', error);
	} else {
		// Copy the files and directories
		ncp(source, destination, { filter: filterFunction }, (error) => {
			if (error) {
				console.error('Error occurred:', error);
			} else {
				console.log('Files copied');
			}
		});
	}
});
