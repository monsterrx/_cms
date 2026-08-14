import CardDropdown from './CardDropdown';

export const selectControlClassName = 'relative min-h-11 w-full min-w-0 max-w-full rounded-md border border-line bg-surface px-3 pr-10 text-left text-sm text-ink outline-none transition-colors hover:bg-canvas/40 focus:border-rx-blue focus:ring-0 disabled:cursor-not-allowed disabled:opacity-60';

export default function SelectControl({
    ariaLabel,
    className = '',
    onChange,
    options = [],
    placeholder = '',
    value,
    ...props
}) {
    const normalizedOptions = options.map((option) => ({
        ...option,
        label: String(option.label ?? option.value ?? ''),
    }));

    return (
        <CardDropdown
            {...props}
            ariaLabel={ariaLabel}
            buttonClassName={`${selectControlClassName} ${className}`}
            onChange={onChange}
            options={normalizedOptions}
            placeholder={placeholder}
            value={value}
        />
    );
}
