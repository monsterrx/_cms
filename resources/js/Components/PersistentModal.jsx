import { useEffect, useRef, useState } from 'react';
import { createPortal } from 'react-dom';
import Icon from './Icon';

export default function PersistentModal({ children, dirty, footer, kicker = 'Record editor', confirmOnClose = false, open, onClose, title }) {
    const [confirmingDiscard, setConfirmingDiscard] = useState(0);
    const dialogRef = useRef(null);

    useEffect(() => {
        if (!open) {
            setConfirmingDiscard(0);
            return undefined;
        }

        const previousFocus = document.activeElement;
        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        dialogRef.current?.focus();

        return () => {
            document.body.style.overflow = previousOverflow;
            previousFocus?.focus();
        };
    }, [open]);

    useEffect(() => {
        if (confirmingDiscard) dialogRef.current?.querySelector('[role="alertdialog"] button')?.focus();
        else if (open) dialogRef.current?.focus();
    }, [confirmingDiscard]);

    if (!open) {
        return null;
    }

    const requestClose = (forceConfirm = false) => {
        if (dirty || confirmOnClose || forceConfirm) {
            setConfirmingDiscard(1);
            return;
        }

        onClose();
    };

    return createPortal(
        <div onMouseDown={(event) => { if (event.target === event.currentTarget) { event.preventDefault(); requestClose(true); } }} className="fixed inset-0 z-[90] flex items-center justify-center bg-black/70 p-3 backdrop-blur-sm sm:p-6">
            <section
                aria-labelledby="record-modal-title"
                aria-modal="true"
                className="relative flex max-h-[92vh] w-full max-w-4xl flex-col overflow-hidden rounded-xl border border-line bg-surface text-ink shadow-2xl"
                ref={dialogRef}
                role="dialog"
                tabIndex={-1}
                onKeyDown={(event) => {
                    if (event.defaultPrevented || event.nativeEvent.isComposing) return;
                    if (event.key === 'Escape') {
                        event.preventDefault(); event.stopPropagation();
                        if (confirmingDiscard) setConfirmingDiscard(0); else requestClose();
                    }
                    if (event.key === 'Tab') {
                        const scope = confirmingDiscard ? dialogRef.current.querySelector('[role="alertdialog"]') : dialogRef.current;
                        const controls = Array.from(scope.querySelectorAll('button:not(:disabled), input:not(:disabled), textarea:not(:disabled), select:not(:disabled), [contenteditable="true"], [tabindex="0"]')).filter((node) => node.getClientRects().length);
                        const first = controls[0], last = controls.at(-1);
                        if (event.shiftKey && (document.activeElement === first || !scope.contains(document.activeElement))) { event.preventDefault(); last?.focus(); }
                        else if (!event.shiftKey && (document.activeElement === last || document.activeElement === dialogRef.current)) { event.preventDefault(); first?.focus(); }
                    }
                    if (event.key === 'Enter' && (event.shiftKey || event.ctrlKey || event.altKey || event.metaKey)) {
                        if (!event.target.isContentEditable && event.target.tagName !== 'TEXTAREA') event.preventDefault();
                        return;
                    }
                    if (event.key === 'Enter' && !confirmingDiscard && !event.target.isContentEditable && !['TEXTAREA', 'BUTTON', 'SELECT'].includes(event.target.tagName) && event.target.getAttribute('role') !== 'combobox') {
                        event.preventDefault();
                        const form = dialogRef.current.querySelector('form');
                        const submit = dialogRef.current.querySelector('button[type="submit"]');
                        if (submit && !submit.disabled) form?.requestSubmit(submit);
                    }
                }}
            >
                <header className="flex h-16 shrink-0 items-center justify-between border-b border-line px-5 sm:px-6">
                    <div className="min-w-0">
                        <p className="rx-kicker">{kicker}</p>
                        <h2 className="truncate font-heading text-lg font-semibold uppercase tracking-wide" id="record-modal-title">
                            {title}
                        </h2>
                    </div>
                    <button
                        aria-label="Close form"
                        className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-ink-muted transition-colors hover:bg-red-500 hover:text-white"
                        onClick={() => requestClose()}
                        type="button"
                    >
                        <Icon className="h-5 w-5" name="close" />
                    </button>
                </header>

                <div className="min-h-0 flex-1 overflow-y-auto px-5 py-8 sm:px-8 sm:py-10">{children}</div>

                {footer && (
                    <footer className="shrink-0 border-t border-line bg-surface-muted/40 px-5 py-4 sm:px-6">
                        {footer}
                    </footer>
                )}

                {confirmingDiscard > 0 && (
                    <div className="absolute inset-0 z-10 flex items-center justify-center bg-black/70 p-6 backdrop-blur-sm">
                        <div className="w-full max-w-md rounded-xl border border-line bg-surface p-6 shadow-2xl" role="alertdialog" aria-modal="true">
                            <span className="flex h-11 w-11 items-center justify-center rounded-lg bg-red-500/15 text-red-500">
                                <Icon name="alert" />
                            </span>
                            <h3 className="mt-5 font-heading text-xl font-semibold uppercase tracking-wide">{confirmingDiscard === 2 ? 'Are you sure?' : 'Discard your changes?'}</h3>
                            <p className="mt-2 text-sm leading-6 text-ink-muted">
                                All unsaved changes in this form will be permanently lost.
                            </p>
                            <div className="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                                <button className="rx-button-secondary" onClick={() => setConfirmingDiscard(0)} type="button">
                                    Keep editing
                                </button>
                                <button className="rx-button bg-red-500 text-white hover:bg-red-600" onClick={() => { if (confirmingDiscard === 1) setConfirmingDiscard(2); else onClose(); }} type="button">
                                    Discard changes
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </section>
        </div>,
        document.body,
    );
}
