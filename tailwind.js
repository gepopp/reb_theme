module.exports = {
	mode: 'jit',
	purge:{
		content: [
			'./**/*.php',
			'./dist/**/*.js',
		],
		safelist: [
			'line-clamp-3',
			'three-lines'
		]
	},
	prefix: '',
	important: false,
	separator: ':',
	theme: {
		container: {
			center: true,
			padding: {
				DEFAULT: '2rem',
				md: '0rem',
			},
			screens: {
				'sm': '640px',
				'md': '768px',
				'lg': '1024px',
			}
		},
		extend: {
			borderWidth: {
				15 : '15px'
			},
			colors: {
				transparent: 'transparent',
				black: '#000',
				white: '#fff',
				warning: '#F53F39',
				success: '#227D1A',
				logo : '#1a1a18'
			},
			spacing: {
				px: '1px',
				'15p':'15%',
				'20p':'20%',
				'25p':'25%',
				'75p':'75%',
				'16by9': '56.25%',
				'100': '100%'
			},
			fontFamily: {
				sans: [
					'"Helvetica Neue"',
					'sans-serif'
				],
				serif: [
					'Georgia',
					'Cambria',
					'"Times New Roman"',
					'Times',
					'serif',
				],
				mono: [
					'Menlo',
					'Monaco',
					'Consolas',
					'"Liberation Mono"',
					'"Courier New"',
					'monospace',
				],
			},
			height: theme => ({
				auto: 'auto',
				...theme('spacing'),
				full: '100%',
				screen: '100vh',
				'screen-75': '75vh',
				200 : '200px'
			}),
		},
		transitionProperty: {
			'height': 'height'
		},
		maxHeight: {
			64 : '16rem'
		}
	},
	variants: {
		alignContent: ['responsive'],
		alignItems: ['responsive'],
		alignSelf: ['responsive'],
		appearance: ['responsive'],
		backgroundAttachment: ['responsive'],
		backgroundColor: ['responsive', 'hover', 'focus'],
		backgroundImage: ['hover', 'focus'],
		backgroundPosition: ['responsive'],
		backgroundRepeat: ['responsive'],
		backgroundSize: ['responsive'],
		borderCollapse: ['responsive'],
		borderColor: ['responsive', 'hover', 'focus'],
		borderRadius: ['responsive'],
		borderStyle: ['responsive'],
		borderWidth: ['responsive'],
		boxShadow: ['responsive', 'hover', 'focus'],
		cursor: ['responsive'],
		display: ['responsive'],
		fill: ['responsive'],
		flex: ['responsive'],
		flexDirection: ['responsive'],
		flexGrow: ['responsive'],
		flexShrink: ['responsive'],
		flexWrap: ['responsive'],
		float: ['responsive'],
		fontFamily: ['responsive'],
		fontSize: ['responsive'],
		fontSmoothing: ['responsive'],
		fontStyle: ['responsive'],
		fontWeight: ['responsive', 'hover', 'focus'],
		height: ['responsive'],
		inset: ['responsive'],
		justifyContent: ['responsive'],
		letterSpacing: ['responsive'],
		lineHeight: ['responsive'],
		listStylePosition: ['responsive'],
		listStyleType: ['responsive'],
		margin: ['responsive'],
		maxHeight: ['responsive'],
		maxWidth: ['responsive'],
		minHeight: ['responsive'],
		minWidth: ['responsive'],
		objectFit: ['responsive'],
		objectPosition: ['responsive'],
		opacity: ['responsive'],
		order: ['responsive'],
		outline: ['responsive', 'focus'],
		overflow: ['responsive'],
		padding: ['responsive'],
		pointerEvents: ['responsive'],
		position: ['responsive'],
		resize: ['responsive'],
		stroke: ['responsive'],
		tableLayout: ['responsive'],
		textAlign: ['responsive'],
		textColor: ['responsive', 'hover', 'focus'],
		textDecoration: ['responsive', 'hover', 'focus'],
		textTransform: ['responsive'],
		userSelect: ['responsive'],
		verticalAlign: ['responsive'],
		visibility: ['responsive'],
		whitespace: ['responsive'],
		width: ['responsive'],
		wordBreak: ['responsive'],
		zIndex: ['responsive'],
	},
	plugins: [

		require('tailwind-scrollbar'),
		require('@tailwindcss/line-clamp'),

	],
}