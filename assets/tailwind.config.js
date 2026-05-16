tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'sans-serif'],
                display: ['Fraunces', 'serif'],
            },
            colors: {
                brand: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                },
                peach: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    400: '#fb923c',
                    500: '#f97316',
                },
                sky: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                },
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
            animation: {
                'fade-up': 'fadeUp 0.6s ease both',
                'fade-in': 'fadeIn 0.5s ease both',
            },
            keyframes: {
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },
        },
    },
};
