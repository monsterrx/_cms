function displayDate(value) {
    if (!value) return 'Not published';

    const normalized = String(value).includes('T') ? value : String(value).replace(' ', 'T');
    const date = new Date(normalized);

    if (Number.isNaN(date.getTime())) return String(value);

    return new Intl.DateTimeFormat('en-PH', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(date);
}

function safePreviewHtml(value) {
    if (typeof DOMParser === 'undefined') return '';

    const document = new DOMParser().parseFromString(String(value ?? ''), 'text/html');
    const allowedTags = new Set(['A', 'B', 'BLOCKQUOTE', 'BR', 'EM', 'H2', 'H3', 'I', 'IMG', 'LI', 'OL', 'P', 'STRONG', 'U', 'UL']);
    const dangerousTags = new Set(['EMBED', 'FORM', 'IFRAME', 'INPUT', 'LINK', 'MATH', 'META', 'OBJECT', 'SCRIPT', 'STYLE', 'SVG']);

    Array.from(document.body.querySelectorAll('*')).forEach((element) => {
        if (dangerousTags.has(element.tagName)) {
            element.remove();
            return;
        }

        if (!allowedTags.has(element.tagName)) {
            element.replaceWith(...element.childNodes);
            return;
        }

        Array.from(element.attributes).forEach((attribute) => {
            if (!['href', 'src', 'alt', 'title', 'target', 'rel'].includes(attribute.name.toLowerCase())) {
                element.removeAttribute(attribute.name);
            }
        });

        if (element.tagName === 'A') {
            const href = element.getAttribute('href') ?? '';
            if (!/^(https?:|mailto:|tel:|\/|#)/i.test(href)) element.removeAttribute('href');
            element.setAttribute('rel', 'noopener noreferrer');
        }

        if (element.tagName === 'IMG') {
            const src = element.getAttribute('src') ?? '';
            if (!/^(https?:\/\/|\/(?!\/))/i.test(src)) element.remove();
        }
    });

    return document.body.innerHTML;
}

export default function ArticlePreview({ category, contents, record, preview }) {
    const image = record?._display?.image;

    return (
        <section aria-label="Article website preview" className="rounded-xl border border-line bg-canvas/50 p-3 sm:p-6">
            <div className="mb-4 flex items-center justify-between gap-3">
                <div>
                    <p className="rx-kicker">Website preview</p>
                    <p className="mt-1 text-xs text-ink-muted">Based on the current RX93.1 article layout.</p>
                </div>
                <span className="rounded-sm bg-[#f4f900] px-2 py-1 text-[0.65rem] font-bold uppercase tracking-wide text-neutral-950">Preview</span>
            </div>

            <article className="mx-auto max-w-[52rem] rounded bg-[#282828] text-[#f8f9fa]">
                <div className="p-5 sm:p-8">
                    <h1 className="font-heading text-2xl font-bold leading-tight text-[#f4f900] sm:text-[2rem] sm:leading-[1.25]">
                        {record?.title || 'Untitled article'}
                    </h1>
                    <p className="mt-4 text-lg font-light leading-7 text-[#f8f9fa] sm:text-[1.35rem] sm:leading-8">
                        {record?.heading || 'Add an article heading to preview the introduction.'}
                    </p>

                    {image && (
                        <img
                            alt={record?.title || 'Article hero'}
                            className="mt-6 aspect-square w-full bg-neutral-900 object-cover"
                            src={image}
                        />
                    )}

                    <dl className="mt-6 grid gap-x-6 gap-y-2 border-y border-white/10 py-4 text-sm sm:grid-cols-2">
                        <div><dt className="inline text-white/55">Category: </dt><dd className="inline">{category || preview?.category || 'Uncategorized'}</dd></div>
                        <div><dt className="inline text-white/55">Published Date: </dt><dd className="inline">{displayDate(record?.published_at)}</dd></div>
                        <div><dt className="inline text-white/55">Last Update: </dt><dd className="inline">{displayDate(record?.updated_at)}</dd></div>
                        <div><dt className="inline text-white/55">Author: </dt><dd className="inline">{preview?.author || 'Monster RX93.1'}</dd></div>
                    </dl>

                    <div className="mx-auto mt-7 max-w-[46.875rem] text-[1.05rem] font-light leading-7 text-[#f8f9fa] [&_a]:text-[#f4f900] [&_a]:underline [&_blockquote]:border-l-4 [&_blockquote]:border-[#f4f900] [&_blockquote]:pl-4 [&_h2]:mb-3 [&_h2]:mt-7 [&_h2]:text-2xl [&_h2]:font-bold [&_h3]:mb-3 [&_h3]:mt-6 [&_h3]:text-xl [&_h3]:font-bold [&_img]:my-6 [&_img]:h-auto [&_img]:w-full [&_ol]:my-4 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:mb-5 [&_ul]:my-4 [&_ul]:list-disc [&_ul]:pl-6">
                        {(contents ?? []).length === 0 ? (
                            <p className="italic text-white/55">Article content will appear here.</p>
                        ) : (contents ?? []).map((content) => (
                            <div
                                dangerouslySetInnerHTML={{ __html: safePreviewHtml(content.content) }}
                                key={content.id}
                            />
                        ))}
                    </div>

                    <div className="mt-10 border-t border-white/10 pt-5">
                        <p className="font-heading text-sm font-bold uppercase tracking-wide text-[#f4f900]">Related Articles</p>
                        {(preview?.related ?? []).length === 0 && <p className="mt-2 text-sm text-white/55">Related articles not found</p>}
                    </div>
                </div>
            </article>
        </section>
    );
}
