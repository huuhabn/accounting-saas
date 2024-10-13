import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './resources/views/**/*.blade.php',
        './app/Filament/Company/**/*.php',
        './resources/views/livewire/company/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/views/vendor/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                white: '#F3F4F6',
                platinum: '#E8E9EB',
            },
            transitionTimingFunction: {
                'ease-smooth': 'cubic-bezier(0.08, 0.52, 0.52, 1)',
            }
        }
    }
}
