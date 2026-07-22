const STATUS_MESSAGES = {
    400: ['Request not accepted', 'Check the information and try again.'],
    401: ['Sign in required', 'Your session is no longer active. Sign in and try again.'],
    403: ['Access denied', 'You do not have permission to perform this action.'],
    404: ['Not found', 'The requested content could not be found.'],
    405: ['Action unavailable', 'This action is not supported here.'],
    408: ['Request timed out', 'The server took too long to respond. Try again.'],
    409: ['Changes conflict', 'The content changed elsewhere. Refresh before trying again.'],
    419: ['Session expired', 'Refresh the page before trying again.'],
    422: ['Check your entries', 'Some submitted information needs attention.'],
    429: ['Too many requests', 'Wait a moment before trying again.'],
    500: ['Something went wrong', 'The system could not complete the request. Your work is safe.'],
    502: ['Service connection failed', 'A required service is not responding. Try again shortly.'],
    503: ['Service unavailable', 'The system is temporarily unavailable. Try again shortly.'],
    504: ['Service timed out', 'A required service took too long to respond.'],
};

const CODE_MESSAGES = {
    DATABASE_ERROR: ['Data service error', 'The request could not be completed. Try again or contact support.'],
    DATABASE_UNAVAILABLE: ['Content service unavailable', 'The content database is temporarily unavailable.'],
    DATA_CONFLICT: ['Content conflict', 'This record conflicts with existing content. Review it and try again.'],
    FORBIDDEN: STATUS_MESSAGES[403],
    METHOD_NOT_ALLOWED: STATUS_MESSAGES[405],
    NOT_FOUND: STATUS_MESSAGES[404],
    SERVER_ERROR: STATUS_MESSAGES[500],
    SESSION_EXPIRED: STATUS_MESSAGES[419],
    UNAUTHENTICATED: STATUS_MESSAGES[401],
    VALIDATION_ERROR: STATUS_MESSAGES[422],
};

const UNSAFE_MESSAGE_PATTERN = /(sqlstate|pdoexception|queryexception|stack trace|vendor[\\/]|select\s.+from|insert\s+into|update\s+.+set|delete\s+from|unknown column|base table|syntax error|undefined index|call to a member function|\.php:\d+)/i;

function safeMessage(message, fallback) {
    if (typeof message !== 'string') {
        return fallback;
    }

    const normalized = message.trim();

    if (!normalized || normalized.length > 240 || UNSAFE_MESSAGE_PATTERN.test(normalized)) {
        return fallback;
    }

    return normalized;
}

/**
 * Converts transport and server failures into a stable UI-safe shape.
 * Raw database, stack, and filesystem details are intentionally discarded.
 */
export function translateError(error) {
    if (error?.code === 'ECONNABORTED') {
        return {
            title: 'Request timed out',
            message: 'The request took too long. Check your connection and try again.',
            code: 'REQUEST_TIMEOUT',
            status: 408,
            fieldErrors: {},
            retryable: true,
        };
    }

    if (!error?.response) {
        const offline = typeof navigator !== 'undefined' && navigator.onLine === false;

        return {
            title: offline ? 'You are offline' : 'Server unreachable',
            message: offline
                ? 'Reconnect to the internet before trying again.'
                : 'The server could not be reached. Try again shortly.',
            code: offline ? 'OFFLINE' : 'NETWORK_ERROR',
            status: 0,
            fieldErrors: {},
            retryable: true,
        };
    }

    const status = Number(error.response.status) || 500;
    const payload = error.response.data && typeof error.response.data === 'object'
        ? error.response.data
        : {};
    const code = typeof payload.code === 'string' ? payload.code : `HTTP_${status}`;
    const [title, fallbackMessage] = CODE_MESSAGES[code]
        ?? STATUS_MESSAGES[status]
        ?? STATUS_MESSAGES[500];

    return {
        title,
        message: safeMessage(payload.message, fallbackMessage),
        code,
        status,
        fieldErrors: payload.errors && typeof payload.errors === 'object' ? payload.errors : {},
        retryable: status === 408 || status === 429 || status >= 500,
    };
}
