import axios from 'axios';
import { useEffect, useState } from 'react';
import PersistentModal from './PersistentModal';
import RichTextEditor from './RichTextEditor';
import Icon from './Icon';
import { useAppState } from '../Contexts/AppStateContext';
import { translateError } from '../lib/errorTranslator';

const maximumScreenshots = 5;

export default function BugReportModal({ onClose, open }) {
    const { notify } = useAppState();
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [screenshots, setScreenshots] = useState([]);
    const [errors, setErrors] = useState({});
    const [message, setMessage] = useState('');
    const [saving, setSaving] = useState(false);

    useEffect(() => {
        if (open) return;

        screenshots.forEach(({ previewUrl }) => URL.revokeObjectURL(previewUrl));
        setTitle('');
        setDescription('');
        setScreenshots([]);
        setErrors({});
        setMessage('');
        setSaving(false);
    }, [open]);

    const addPastedScreenshot = (file) => {
        if (screenshots.length >= maximumScreenshots) {
            setMessage(`A bug report can contain up to ${maximumScreenshots} screenshots.`);
            return null;
        }
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            setMessage('Paste a PNG, JPEG, or WebP screenshot.');
            return null;
        }
        if (file.size > 8 * 1024 * 1024) {
            setMessage('Each screenshot must be 8 MB or smaller.');
            return null;
        }

        const previewUrl = URL.createObjectURL(file);
        setScreenshots((current) => [...current, { file, previewUrl }]);
        setMessage('');
        return previewUrl;
    };

    const submit = async (event) => {
        event.preventDefault();
        setSaving(true);
        setErrors({});
        setMessage('');

        const payload = new FormData();
        payload.append('title', title);
        payload.append('description', description);
        payload.append('page_url', window.location.href);
        screenshots.forEach(({ file }) => payload.append('screenshots[]', file));

        try {
            const response = await axios.post('/api/bug-reports', payload, { silent: true });
            const notificationSent = response.data.data?.notification_sent;
            notify({
                type: notificationSent ? 'success' : 'error',
                title: notificationSent ? 'Bug report submitted' : 'Bug report saved',
                message: response.data.message,
                duration: notificationSent ? 6000 : 9000,
            });
            onClose();
        } catch (error) {
            const translated = translateError(error);
            setErrors(translated.fieldErrors);
            setMessage(translated.message);
        } finally {
            setSaving(false);
        }
    };

    return (
        <PersistentModal
            dirty={Boolean(title || description || screenshots.length)}
            footer={(
                <div className="flex items-center justify-between gap-4">
                    <p className="text-xs text-ink-muted">The current page and signed-in account are included automatically.</p>
                    <button className="rx-button" disabled={saving} form="bug-report-form" type="submit">
                        {saving ? 'Sending...' : 'Send bug report'}
                    </button>
                </div>
            )}
            kicker="Quality feedback"
            onClose={onClose}
            open={open}
            title="Report a bug"
        >
            {message && <p className="mb-5 border-l-4 border-red-500 bg-red-500/10 p-4 text-sm text-red-500" role="alert">{message}</p>}
            <form className="space-y-6" id="bug-report-form" onSubmit={submit}>
                <div>
                    <label className="font-heading text-xs font-semibold uppercase tracking-wide text-ink-muted" htmlFor="bug-report-title">Bug title</label>
                    <input
                        className={`mt-2 min-h-12 w-full rounded-md border bg-transparent px-4 text-sm text-ink outline-none transition-colors focus:border-rx-blue focus:ring-0 ${errors.title ? 'border-red-500' : 'border-line'}`}
                        id="bug-report-title"
                        maxLength={255}
                        onChange={(event) => setTitle(event.target.value)}
                        placeholder="Briefly describe what went wrong"
                        value={title}
                    />
                    {errors.title && <p className="mt-1.5 text-xs text-red-500">{errors.title}</p>}
                </div>

                <div>
                    <RichTextEditor
                        disabled={false}
                        error={errors.description}
                        field={{ name: 'bug-description', label: 'What happened?' }}
                        onChange={(_name, value) => setDescription(value)}
                        onPasteImage={addPastedScreenshot}
                        required
                        value={description}
                    />
                    <p className="mt-2 text-xs leading-5 text-ink-muted">Paste screenshots directly into the editor. Up to five PNG, JPEG, or WebP files, 8 MB each.</p>
                </div>

                {screenshots.length > 0 && (
                    <div>
                        <p className="mb-3 text-xs font-semibold uppercase tracking-wide text-ink-muted">Attached screenshots</p>
                        <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            {screenshots.map((screenshot, index) => (
                                <div className="relative overflow-hidden rounded-md border border-line bg-canvas" key={screenshot.previewUrl}>
                                    <img alt={`Pasted screenshot ${index + 1}`} className="aspect-video w-full object-contain" src={screenshot.previewUrl} />
                                    <button
                                        aria-label={`Remove screenshot ${index + 1}`}
                                        className="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-md bg-neutral-950/80 text-white transition-colors hover:bg-red-500"
                                        onClick={() => {
                                            URL.revokeObjectURL(screenshot.previewUrl);
                                            setDescription((current) => current.replaceAll(`<img src="${screenshot.previewUrl}">`, ''));
                                            setScreenshots((current) => current.filter((candidate) => candidate.previewUrl !== screenshot.previewUrl));
                                        }}
                                        type="button"
                                    >
                                        <Icon className="h-4 w-4" name="close" />
                                    </button>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </form>
        </PersistentModal>
    );
}
