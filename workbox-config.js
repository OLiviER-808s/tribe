module.exports = {
	globDirectory: 'public/',
	globPatterns: [
		'**/*.{jpg,ico,png,svg,json,css,js}'
	],
	swDest: 'public/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};