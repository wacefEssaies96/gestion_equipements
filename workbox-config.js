module.exports = {
	globDirectory: 'public/',
	globPatterns: [
		'**/*.txt'
	],
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	],
	swDest: 'public/sw.js'
};