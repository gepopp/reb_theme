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
				gray: {
					100: '#f7fafc',
					200: '#edf2f7',
					300: '#e2e8f0',
					400: '#cbd5e0',
					500: '#a0aec0',
					600: '#718096',
					700: '#4a5568',
					800: '#2d3748',
					900: '#1a202c',
				},
				primary: {
					100: '#5C97D0',
					5:'#F6F9FC'
				},
				inland:{
					100: '#bcb603'
				},
				aktuelles:{
					100: '#dd3333'
				},
				ausland:{
					100: '#1e73be'
				},
				menschen:{
					100: '#dd9933'
				},
				'trends-digitalisierung':{
					100: '#81d742'
				}
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
					'-apple-system',
					'BlinkMacSystemFont',
					'"Segoe UI"',
					'Roboto',
					'"Helvetica Neue"',
					'Arial',
					'"Noto Sans"',
					'sans-serif',
					'"Apple Color Emoji"',
					'"Segoe UI Emoji"',
					'"Segoe UI Symbol"',
					'"Noto Color Emoji"',
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