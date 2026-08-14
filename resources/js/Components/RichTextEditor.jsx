import { useEffect, useRef, useState } from 'react';
import SelectControl from './SelectControl';

const toolbarActions = [
    ['Bold', 'bold'],
    ['Italic', 'italic'],
    ['Underline', 'underline'],
    ['Bullets', 'insertUnorderedList'],
    ['Numbers', 'insertOrderedList'],
    ['Quote', 'formatBlock', 'blockquote'],
];

function hasVisibleContent(html) {
    if (/<img\b/i.test(String(html || ''))) {
        return true;
    }

    return String(html || '')
        .replace(/<br\s*\/?>/gi, '')
        .replace(/<[^>]+>/g, '')
        .replace(/&nbsp;|&#160;/gi, ' ')
        .trim() !== '';
}

export default function RichTextEditor({ disabled, error, field, onChange, onPasteImage, required, value }) {
    const editorRef = useRef(null);
    const [empty, setEmpty] = useState(!hasVisibleContent(value || ''));
    const [focused, setFocused] = useState(false);

    useEffect(() => {
        const editor = editorRef.current;
        const html = value || '';

        if (editor && document.activeElement !== editor && editor.innerHTML !== html) {
            editor.innerHTML = html;
            setEmpty(!hasVisibleContent(html));
        }
    }, [value]);

    const emitChange = () => {
        const html = editorRef.current?.innerHTML ?? '';
        setEmpty(!hasVisibleContent(html));
        onChange(field.name, html);
    };

    const execute = (command, argument = null) => {
        editorRef.current?.focus();
        document.execCommand(command, false, argument);
        emitChange();
    };

    const createLink = () => {
        const url = window.prompt('Enter an HTTPS URL, email address, or telephone link.');
        if (!url) {
            return;
        }

        execute('createLink', url);
    };

    const pasteImage = (event) => {
        if (!onPasteImage || disabled) {
            return;
        }

        const clipboardImage = Array.from(event.clipboardData?.items ?? [])
            .find((item) => item.kind === 'file' && item.type.startsWith('image/'));
        const imageFile = clipboardImage?.getAsFile();
        if (!imageFile) {
            return;
        }

        const previewUrl = onPasteImage(imageFile);
        if (!previewUrl) {
            return;
        }

        event.preventDefault();
        execute('insertImage', previewUrl);
    };

    const labelColor = error ? 'text-red-500' : (focused ? 'text-rx-blue' : 'text-ink-muted');

    return (
        <div className="md:col-span-2">
            <div className={`overflow-hidden rounded-lg border bg-transparent transition-colors ${error ? 'border-red-500' : (focused ? 'border-rx-blue' : 'border-line')}`}>
                <div className="flex flex-wrap gap-1 border-b border-line bg-canvas/50 p-2" role="toolbar" aria-label={`${field.label} formatting`}>
                    <SelectControl
                        ariaLabel="Text style"
                        className="h-9 min-h-9 w-36 text-xs font-semibold"
                        disabled={disabled}
                        onChange={(style) => {
                            execute('formatBlock', style);
                        }}
                        options={[
                            { value: 'p', label: 'Paragraph' },
                            { value: 'h2', label: 'Heading 2' },
                            { value: 'h3', label: 'Heading 3' },
                        ]}
                        value="p"
                    />
                    {toolbarActions.map(([label, command, argument]) => (
                        <button
                            className="h-9 rounded-md px-3 text-xs font-semibold text-ink-muted transition-colors hover:bg-surface hover:text-rx-blue disabled:opacity-50"
                            disabled={disabled}
                            key={command}
                            onMouseDown={(event) => event.preventDefault()}
                            onClick={() => execute(command, argument)}
                            type="button"
                        >
                            {label}
                        </button>
                    ))}
                    <button
                        className="h-9 rounded-md px-3 text-xs font-semibold text-ink-muted transition-colors hover:bg-surface hover:text-rx-blue disabled:opacity-50"
                        disabled={disabled}
                        onMouseDown={(event) => event.preventDefault()}
                        onClick={createLink}
                        type="button"
                    >
                        Link
                    </button>
                    <button
                        className="h-9 rounded-md px-3 text-xs font-semibold text-ink-muted transition-colors hover:bg-surface hover:text-rx-blue disabled:opacity-50"
                        disabled={disabled}
                        onMouseDown={(event) => event.preventDefault()}
                        onClick={() => execute('removeFormat')}
                        type="button"
                    >
                        Clear style
                    </button>
                </div>
                <div className="relative">
                    <label
                        className={`pointer-events-none absolute left-4 top-3 z-10 font-heading text-[0.65rem] font-semibold uppercase tracking-[0.1em] transition-colors ${labelColor}`}
                        htmlFor={`field-${field.name}`}
                    >
                        {field.label}{required && <span className="ml-1 text-red-500">*</span>}
                    </label>
                    {empty && !focused && (
                        <span className="pointer-events-none absolute left-4 top-10 text-sm text-ink-muted">
                            Write and format {field.label.toLowerCase()}...
                        </span>
                    )}
                    <div
                        aria-describedby={error ? `field-${field.name}-error` : undefined}
                        aria-invalid={Boolean(error)}
                        className="min-h-52 px-4 pb-4 pt-10 text-sm leading-7 text-ink outline-none [&_a]:text-rx-blue [&_a]:underline [&_blockquote]:border-l-4 [&_blockquote]:border-rx-blue [&_blockquote]:pl-4 [&_h2]:text-xl [&_h2]:font-semibold [&_h3]:text-lg [&_h3]:font-semibold [&_img]:my-4 [&_img]:max-h-96 [&_img]:max-w-full [&_img]:rounded-md [&_img]:border [&_img]:border-line [&_img]:object-contain [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:mb-3 [&_ul]:list-disc [&_ul]:pl-6"
                        contentEditable={!disabled}
                        id={`field-${field.name}`}
                        onBlur={() => {
                            setFocused(false);
                            emitChange();
                        }}
                        onFocus={() => setFocused(true)}
                        onInput={emitChange}
                        onPaste={pasteImage}
                        ref={editorRef}
                        role="textbox"
                        suppressContentEditableWarning
                    />
                </div>
            </div>
            {error && <p className="mt-1.5 text-xs text-red-500" id={`field-${field.name}-error`}>{error}</p>}
        </div>
    );
}
