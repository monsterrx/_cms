function normalizeBasePath(value) {
    const trimmed = String(value ?? '').trim().replace(/^\/+|\/+$/g, '');

    return trimmed ? `/${trimmed}` : '';
}

function isAbsoluteUrl(value) {
    return /^(?:[a-z][a-z\d+.-]*:)?\/\//i.test(value);
}

export function getAppBasePath() {
    return normalizeBasePath(
        document.querySelector('meta[name="app-base-path"]')?.content,
    );
}

export function appPath(path = '/') {
    const value = String(path || '/');

    if (isAbsoluteUrl(value) || value.startsWith('#')) {
        return value;
    }

    const basePath = getAppBasePath();
    const normalizedPath = `/${value.replace(/^\/+/, '')}`;

    if (!basePath) {
        return normalizedPath;
    }

    if (normalizedPath === basePath || normalizedPath.startsWith(`${basePath}/`)) {
        return normalizedPath;
    }

    return normalizedPath === '/' ? `${basePath}/` : `${basePath}${normalizedPath}`;
}

export function relativeAppPath(url = '/') {
    let pathname;

    try {
        pathname = new URL(String(url), window.location.origin).pathname;
    } catch {
        pathname = String(url).split(/[?#]/, 1)[0] || '/';
    }

    const basePath = getAppBasePath();

    if (!basePath) {
        return pathname || '/';
    }

    if (pathname === basePath || pathname === `${basePath}/`) {
        return '/';
    }

    return pathname.startsWith(`${basePath}/`)
        ? pathname.slice(basePath.length)
        : pathname;
}
