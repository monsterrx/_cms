import { useEffect, useRef, useState } from 'react';

const clamp = (value, minimum, maximum) => Math.min(maximum, Math.max(minimum, value));

function sourceRectangle(image, crop, zoom, offsetX, offsetY) {
    const targetRatio = crop.width / crop.height;
    const sourceRatio = image.naturalWidth / image.naturalHeight;
    let baseWidth;
    let baseHeight;

    if (sourceRatio > targetRatio) {
        baseHeight = image.naturalHeight;
        baseWidth = baseHeight * targetRatio;
    } else {
        baseWidth = image.naturalWidth;
        baseHeight = baseWidth / targetRatio;
    }

    const width = baseWidth / zoom;
    const height = baseHeight / zoom;
    const horizontalRoom = (image.naturalWidth - width) / 2;
    const verticalRoom = (image.naturalHeight - height) / 2;

    return {
        x: horizontalRoom + ((offsetX / 100) * horizontalRoom),
        y: verticalRoom + ((offsetY / 100) * verticalRoom),
        width,
        height,
    };
}

function drawCrop(canvas, image, crop, zoom, offsetX, offsetY) {
    const context = canvas.getContext('2d');
    const source = sourceRectangle(image, crop, zoom, offsetX, offsetY);

    context.clearRect(0, 0, canvas.width, canvas.height);
    context.imageSmoothingEnabled = true;
    context.imageSmoothingQuality = 'high';
    context.drawImage(
        image,
        source.x,
        source.y,
        source.width,
        source.height,
        0,
        0,
        canvas.width,
        canvas.height,
    );
}

export default function ImageCropField({
    disabled,
    error,
    field,
    onChange,
    onCropStatusChange,
    value,
}) {
    const canvasRef = useRef(null);
    const dragRef = useRef(null);
    const [image, setImage] = useState(null);
    const [objectUrl, setObjectUrl] = useState('');
    const [sourceFile, setSourceFile] = useState(null);
    const [zoom, setZoom] = useState(1);
    const [offsetX, setOffsetX] = useState(0);
    const [offsetY, setOffsetY] = useState(0);
    const [localError, setLocalError] = useState('');
    const [applied, setApplied] = useState(false);
    const crop = field.crop;
    const previewWidth = Math.min(crop.width, 720);
    const previewHeight = Math.round(previewWidth * (crop.height / crop.width));

    useEffect(() => () => {
        if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
        }
    }, [objectUrl]);

    useEffect(() => {
        if (image && canvasRef.current) {
            drawCrop(canvasRef.current, image, crop, zoom, offsetX, offsetY);
        }
    }, [crop, image, offsetX, offsetY, zoom]);

    const markPending = () => {
        if (sourceFile) {
            setApplied(false);
            onCropStatusChange(field.name, false);
        }
    };

    const selectImage = (event) => {
        const file = event.target.files?.[0];
        event.target.value = '';

        if (!file) {
            return;
        }

        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            setLocalError('Choose a JPG, PNG, or WebP image.');
            onCropStatusChange(field.name, true);
            return;
        }

        if (file.size > 12 * 1024 * 1024) {
            setLocalError('The source image must not exceed 12 MB.');
            onCropStatusChange(field.name, true);
            return;
        }

        const nextUrl = URL.createObjectURL(file);
        const nextImage = new Image();

        nextImage.onload = () => {
            if (nextImage.naturalWidth < crop.width || nextImage.naturalHeight < crop.height) {
                URL.revokeObjectURL(nextUrl);
                setLocalError(`Choose an image at least ${crop.width} × ${crop.height} pixels.`);
                onCropStatusChange(field.name, true);
                return;
            }

            setObjectUrl(nextUrl);
            setImage(nextImage);
            setSourceFile(file);
            setZoom(1);
            setOffsetX(0);
            setOffsetY(0);
            setApplied(false);
            setLocalError('');
            onCropStatusChange(field.name, false);
        };
        nextImage.onerror = () => {
            URL.revokeObjectURL(nextUrl);
            setLocalError('The selected image could not be opened.');
            onCropStatusChange(field.name, true);
        };
        nextImage.src = nextUrl;
    };

    const applyCrop = async () => {
        if (!image || !sourceFile) {
            return;
        }

        const output = document.createElement('canvas');
        output.width = crop.width;
        output.height = crop.height;
        drawCrop(output, image, crop, zoom, offsetX, offsetY);

        const blob = await new Promise((resolve) => output.toBlob(resolve, 'image/jpeg', 0.92));
        if (!blob) {
            setLocalError('The cropped image could not be prepared.');
            return;
        }

        const baseName = sourceFile.name
            .replace(/\.[^.]+$/, '')
            .replace(/[^a-z0-9_-]+/gi, '-')
            .replace(/^-+|-+$/g, '')
            .slice(0, 80) || 'image';
        const croppedFile = new File(
            [blob],
            `${baseName}-${crop.width}x${crop.height}.jpg`,
            { type: 'image/jpeg', lastModified: Date.now() },
        );

        onChange(field.name, croppedFile);
        onCropStatusChange(field.name, true);
        setApplied(true);
        setLocalError('');
    };

    const updateZoom = (event) => {
        setZoom(Number(event.target.value));
        markPending();
    };

    const updateOffset = (axis, nextValue) => {
        if (axis === 'x') {
            setOffsetX(nextValue);
        } else {
            setOffsetY(nextValue);
        }
        markPending();
    };

    const startDrag = (event) => {
        if (!image) {
            return;
        }

        event.currentTarget.setPointerCapture(event.pointerId);
        dragRef.current = {
            pointerX: event.clientX,
            pointerY: event.clientY,
            offsetX,
            offsetY,
        };
    };

    const dragImage = (event) => {
        if (!dragRef.current || !canvasRef.current) {
            return;
        }

        const rect = canvasRef.current.getBoundingClientRect();
        const horizontal = dragRef.current.offsetX
            - (((event.clientX - dragRef.current.pointerX) / rect.width) * 200);
        const vertical = dragRef.current.offsetY
            - (((event.clientY - dragRef.current.pointerY) / rect.height) * 200);

        setOffsetX(clamp(horizontal, -100, 100));
        setOffsetY(clamp(vertical, -100, 100));
        markPending();
    };

    const stopDrag = () => {
        dragRef.current = null;
    };

    return (
        <div className="md:col-span-2">
            <div className={`overflow-hidden rounded-lg border bg-canvas/30 transition-colors ${error || localError ? 'border-red-500' : 'border-line focus-within:border-rx-blue'}`}>
                <div className="flex flex-col gap-3 border-b border-line px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p className="font-heading text-xs font-semibold uppercase tracking-[0.12em] text-ink">{field.label}</p>
                        <p className="mt-1 text-xs text-ink-muted">
                            {crop.label || 'Cropped image'} · Required output {crop.width} × {crop.height}px
                        </p>
                        {field.help && <p className="mt-1 max-w-2xl text-xs leading-5 text-ink-muted">{field.help}</p>}
                    </div>
                    <label className={`rx-button-secondary cursor-pointer ${disabled ? 'pointer-events-none opacity-50' : ''}`}>
                        Choose image
                        <input
                            accept="image/jpeg,image/png,image/webp"
                            className="sr-only"
                            disabled={disabled}
                            onChange={selectImage}
                            type="file"
                        />
                    </label>
                </div>

                {image ? (
                    <div className="grid gap-5 p-4 lg:grid-cols-[minmax(0,1fr)_15rem]">
                        <div className="overflow-hidden rounded-md border border-line bg-neutral-950">
                            <canvas
                                aria-label={`Crop preview for ${field.label}`}
                                className="block h-auto w-full touch-none cursor-move"
                                height={previewHeight}
                                onPointerCancel={stopDrag}
                                onPointerDown={startDrag}
                                onPointerMove={dragImage}
                                onPointerUp={stopDrag}
                                ref={canvasRef}
                                width={previewWidth}
                            />
                        </div>
                        <div className="space-y-4">
                            <label className="block text-xs font-semibold uppercase tracking-wide text-ink-muted">
                                Zoom
                                <input
                                    className="mt-2 w-full accent-rx-blue"
                                    max="3"
                                    min="1"
                                    onChange={updateZoom}
                                    step="0.01"
                                    type="range"
                                    value={zoom}
                                />
                            </label>
                            <label className="block text-xs font-semibold uppercase tracking-wide text-ink-muted">
                                Horizontal position
                                <input
                                    className="mt-2 w-full accent-rx-blue"
                                    max="100"
                                    min="-100"
                                    onChange={(event) => updateOffset('x', Number(event.target.value))}
                                    step="1"
                                    type="range"
                                    value={offsetX}
                                />
                            </label>
                            <label className="block text-xs font-semibold uppercase tracking-wide text-ink-muted">
                                Vertical position
                                <input
                                    className="mt-2 w-full accent-rx-blue"
                                    max="100"
                                    min="-100"
                                    onChange={(event) => updateOffset('y', Number(event.target.value))}
                                    step="1"
                                    type="range"
                                    value={offsetY}
                                />
                            </label>
                            <button className="rx-button w-full justify-center" onClick={applyCrop} type="button">
                                {applied ? 'Crop applied' : 'Apply crop'}
                            </button>
                            {!applied && <p className="text-xs leading-5 text-rx-yellow">Apply the crop before saving the record.</p>}
                        </div>
                    </div>
                ) : (
                    <div className="px-4 py-5 text-sm text-ink-muted">
                        Existing image: <span className="font-medium text-ink">{typeof value === 'string' && value ? value : 'None'}</span>
                    </div>
                )}
            </div>
            {(error || localError) && <p className="mt-1.5 text-xs text-red-500">{error || localError}</p>}
        </div>
    );
}
