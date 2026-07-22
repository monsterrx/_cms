import Icon from './Icon';
import { useAppState } from '../Contexts/AppStateContext';

const options = [
    { value: 'dark', label: 'Dark', icon: 'moon' },
    { value: 'light', label: 'Light', icon: 'sun' },
    { value: 'system', label: 'Device', icon: 'monitor' },
];

export default function ThemeSwitcher({ compact = false }) {
    const { themePreference, setThemePreference } = useAppState();

    return (
        <div
            aria-label="Color theme"
            className="flex rounded-lg bg-canvas p-1 transition-colors duration-300"
            role="group"
        >
            {options.map((option) => (
                <button
                    aria-label={`Use ${option.label.toLowerCase()} theme`}
                    aria-pressed={themePreference === option.value}
                    className={`flex min-h-9 items-center justify-center gap-2 rounded-md px-2.5 font-heading text-xs font-semibold uppercase tracking-wide transition-all duration-200 ${
                        themePreference === option.value
                            ? 'bg-rx-blue text-neutral-950'
                            : 'text-ink-muted hover:bg-surface-muted hover:text-ink'
                    }`}
                    key={option.value}
                    onClick={() => setThemePreference(option.value)}
                    title={`${option.label} theme`}
                    type="button"
                >
                    <Icon className="h-4 w-4" name={option.icon} />
                    {!compact && <span>{option.label}</span>}
                </button>
            ))}
        </div>
    );
}
