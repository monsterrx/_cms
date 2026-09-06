import axios from 'axios';
import { createContext, useCallback, useContext, useEffect, useMemo, useReducer } from 'react';
import Icon from '../Components/Icon';
import { translateError } from '../lib/errorTranslator';

const THEME_STORAGE_KEY = 'monster-cms:theme';
const NAVIGATION_STORAGE_KEY = 'monster-cms:navigation-visible';
const AppStateContext = createContext(null);
let notificationSequence = 0;

function readStoredValue(key, fallback) {
    try {
        return window.localStorage.getItem(key) ?? fallback;
    } catch {
        return fallback;
    }
}

function initialState() {
    const storedTheme = readStoredValue(THEME_STORAGE_KEY, 'dark');
    const themePreference = ['dark', 'light', 'system'].includes(storedTheme) ? storedTheme : 'dark';
    const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const desktopViewport = window.matchMedia('(min-width: 1024px)').matches;

    return {
        themePreference,
        systemDark,
        navigationVisible: desktopViewport
            ? readStoredValue(NAVIGATION_STORAGE_KEY, 'true') === 'true'
            : false,
        pendingRequests: 0,
        notifications: [],
    };
}

function reducer(state, action) {
    switch (action.type) {
        case 'SET_THEME':
            return { ...state, themePreference: action.value };
        case 'SET_SYSTEM_THEME':
            return { ...state, systemDark: action.value };
        case 'SET_NAVIGATION':
            return { ...state, navigationVisible: action.value };
        case 'REQUEST_STARTED':
            return { ...state, pendingRequests: state.pendingRequests + 1 };
        case 'REQUEST_FINISHED':
            return { ...state, pendingRequests: Math.max(0, state.pendingRequests - 1) };
        case 'ADD_NOTIFICATION':
            return { ...state, notifications: [...state.notifications.slice(-3), action.value] };
        case 'REMOVE_NOTIFICATION':
            return {
                ...state,
                notifications: state.notifications.filter(({ id }) => id !== action.value),
            };
        default:
            return state;
    }
}

function NotificationCenter({ notifications, onDismiss }) {
    return (
        <div
            aria-live="polite"
            className="pointer-events-none fixed inset-x-4 top-4 z-[70] flex flex-col items-end gap-3 sm:left-auto sm:w-96"
        >
            {notifications.map((notification) => (
                <div
                    className={`pointer-events-auto w-full border-l-4 bg-surface p-4 text-ink ring-1 ring-line ${
                        notification.type === 'success' ? 'border-rx-yellow' : 'border-red-500'
                    }`}
                    key={notification.id}
                    role={notification.type === 'error' ? 'alert' : 'status'}
                >
                    <div className="flex items-start gap-3">
                        <Icon
                            className={`mt-0.5 h-5 w-5 shrink-0 ${notification.type === 'success' ? 'text-rx-yellow' : 'text-red-500'}`}
                            name={notification.type === 'success' ? 'check' : 'alert'}
                        />
                        <div className="min-w-0 flex-1">
                            <p className="font-heading text-sm font-semibold uppercase tracking-wide">
                                {notification.title}
                            </p>
                            <p className="mt-1 text-sm leading-5 text-ink-muted">{notification.message}</p>
                        </div>
                        <button
                            aria-label="Dismiss notification"
                            className="text-ink-muted hover:text-ink"
                            onClick={() => onDismiss(notification.id)}
                            type="button"
                        >
                            <Icon className="h-5 w-5" name="close" />
                        </button>
                    </div>
                </div>
            ))}
        </div>
    );
}

export function AppStateProvider({ children }) {
    const [state, dispatch] = useReducer(reducer, undefined, initialState);
    const resolvedTheme = state.themePreference === 'system'
        ? (state.systemDark ? 'dark' : 'light')
        : state.themePreference;

    const dismissNotification = useCallback((id) => {
        dispatch({ type: 'REMOVE_NOTIFICATION', value: id });
    }, []);

    const notify = useCallback(({ type = 'success', title, message, duration = 6000 }) => {
        notificationSequence += 1;
        const id = notificationSequence;

        dispatch({
            type: 'ADD_NOTIFICATION',
            value: { id, type, title, message },
        });

        window.setTimeout(() => dismissNotification(id), duration);
        return id;
    }, [dismissNotification]);

    const setThemePreference = useCallback((value) => {
        if (!['dark', 'light', 'system'].includes(value)) {
            return;
        }

        try {
            window.localStorage.setItem(THEME_STORAGE_KEY, value);
        } catch {
            // The selected theme remains active for this session when storage is unavailable.
        }

        dispatch({ type: 'SET_THEME', value });
    }, []);

    const setNavigationVisible = useCallback((value) => {
        if (window.matchMedia('(min-width: 1024px)').matches) {
            try {
                window.localStorage.setItem(NAVIGATION_STORAGE_KEY, String(value));
            } catch {
                // Navigation remains functional without persistence.
            }
        }

        dispatch({ type: 'SET_NAVIGATION', value });
    }, []);

    useEffect(() => {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        const handleSystemTheme = (event) => {
            dispatch({ type: 'SET_SYSTEM_THEME', value: event.matches });
        };

        if (mediaQuery.addEventListener) {
            mediaQuery.addEventListener('change', handleSystemTheme);
        } else {
            mediaQuery.addListener(handleSystemTheme);
        }

        return () => {
            if (mediaQuery.removeEventListener) {
                mediaQuery.removeEventListener('change', handleSystemTheme);
            } else {
                mediaQuery.removeListener(handleSystemTheme);
            }
        };
    }, []);

    useEffect(() => {
        const isDark = resolvedTheme === 'dark';
        document.documentElement.classList.toggle('dark', isDark);
        document.documentElement.dataset.theme = resolvedTheme;
        document.documentElement.style.colorScheme = resolvedTheme;
    }, [resolvedTheme]);

    useEffect(() => {
        const requestInterceptor = axios.interceptors.request.use(
            (config) => {
                if (config.trackActivity !== false) {
                    dispatch({ type: 'REQUEST_STARTED' });
                }

                return config;
            },
            (error) => Promise.reject(error),
        );
        const responseInterceptor = axios.interceptors.response.use(
            (response) => {
                if (response.config.trackActivity !== false) {
                    dispatch({ type: 'REQUEST_FINISHED' });
                }

                if (!response.config.silent && response.data?.success === true && response.data?.message) {
                    notify({
                        type: 'success',
                        title: 'Completed',
                        message: response.data.message,
                    });
                }

                return response;
            },
            (error) => {
                if (error.config?.trackActivity !== false) {
                    dispatch({ type: 'REQUEST_FINISHED' });
                }

                const translated = translateError(error);

                if (!error.config?.silent) {
                    notify({
                        type: 'error',
                        title: translated.title,
                        message: translated.message,
                        duration: translated.retryable ? 8000 : 6000,
                    });
                }

                return Promise.reject(error);
            },
        );

        return () => {
            axios.interceptors.request.eject(requestInterceptor);
            axios.interceptors.response.eject(responseInterceptor);
        };
    }, [notify]);

    const value = useMemo(() => ({
        ...state,
        resolvedTheme,
        isBusy: state.pendingRequests > 0,
        dismissNotification,
        notify,
        setNavigationVisible,
        setThemePreference,
        toggleNavigation: () => setNavigationVisible(!state.navigationVisible),
    }), [dismissNotification, notify, resolvedTheme, setNavigationVisible, setThemePreference, state]);

    return (
        <AppStateContext.Provider value={value}>
            {children}
            <NotificationCenter
                notifications={state.notifications}
                onDismiss={dismissNotification}
            />
        </AppStateContext.Provider>
    );
}

export function useAppState() {
    const context = useContext(AppStateContext);

    if (!context) {
        throw new Error('useAppState must be used within AppStateProvider.');
    }

    return context;
}
