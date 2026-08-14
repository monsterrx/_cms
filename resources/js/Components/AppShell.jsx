import axios from 'axios';
import { Link, usePage } from '@inertiajs/react';
import { useEffect, useLayoutEffect, useMemo, useRef, useState } from 'react';
import CommandPalette from './CommandPalette';
import CardDropdown from './CardDropdown';
import Icon from './Icon';
import SystemStatus from './SystemStatus';
import ThemeSwitcher from './ThemeSwitcher';
import BugReportModal from './BugReportModal';
import { useAppState } from '../Contexts/AppStateContext';
import { appPath, relativeAppPath } from '../lib/appUrl';

const SIDEBAR_SCROLL_KEY = 'monster-control-room:sidebar-scroll';
const BUG_HINT_KEY = 'monster-control-room:bug-hint-seen';

function Brand() {
    return (
        <Link className="group flex items-center gap-3" href={appPath('/dashboard')}>
            <span className="flex h-10 w-10 items-center justify-center rounded-lg bg-rx-yellow font-heading text-sm font-bold tracking-tight text-neutral-950 transition-transform duration-200 group-hover:scale-105">
                RX
            </span>
            <span>
                <span className="block font-heading text-sm font-bold uppercase tracking-wide text-sidebar-ink">Monster CMS</span>
                <span className="block text-[0.65rem] uppercase tracking-[0.22em] text-rx-blue">RX93.1</span>
            </span>
        </Link>
    );
}

function findActiveContext(navigation, url) {
    for (const section of navigation) {
        if (!url.startsWith(`/workspace/${section.slug}`)) {
            continue;
        }

        const item = section.groups
            .flatMap((group) => group.items)
            .find((candidate) => url === `/workspace/${section.slug}/${candidate.slug}`);

        return { section, item };
    }

    return { section: null, item: null };
}

export default function AppShell({ children }) {
    const { url, props } = usePage();
    const currentPath = relativeAppPath(url);
    const navigation = props.navigation ?? [];
    const activeContext = useMemo(() => findActiveContext(navigation, currentPath), [currentPath, navigation]);
    const [expandedSections, setExpandedSections] = useState(() => (
        activeContext.section ? [activeContext.section.slug] : []
    ));
    const [searchOpen, setSearchOpen] = useState(false);
    const [signingOut, setSigningOut] = useState(false);
    const [switchingStation, setSwitchingStation] = useState(false);
    const [bugReportOpen, setBugReportOpen] = useState(false);
    const [bugHintVisible, setBugHintVisible] = useState(false);
    const sidebarNavigationRef = useRef(null);
    const restoringSidebarScrollRef = useRef(false);
    const user = props.auth?.user;
    const station = props.station ?? { can_switch: false, current: 'mnl', options: [] };
    const {
        isBusy,
        navigationVisible,
        notify,
        setNavigationVisible,
        toggleNavigation,
    } = useAppState();

    useEffect(() => {
        if (!activeContext.section) {
            return;
        }

        setExpandedSections((current) => (
            current.includes(activeContext.section.slug)
                ? current
                : [...current, activeContext.section.slug]
        ));
    }, [activeContext.section]);

    useLayoutEffect(() => {
        const navigationElement = sidebarNavigationRef.current;
        if (!navigationElement) {
            return undefined;
        }

        let savedPosition = 0;
        try {
            savedPosition = Number(window.sessionStorage.getItem(SIDEBAR_SCROLL_KEY) ?? 0);
        } catch {
            // Scroll persistence is optional when browser storage is unavailable.
        }

        restoringSidebarScrollRef.current = true;
        navigationElement.scrollTop = savedPosition;
        const restoreTimer = window.setTimeout(() => {
            navigationElement.scrollTop = savedPosition;
            restoringSidebarScrollRef.current = false;
        }, 350);

        return () => window.clearTimeout(restoreTimer);
    }, [currentPath]);

    useEffect(() => {
        const handleShortcut = (event) => {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                setSearchOpen((current) => !current);
            }
        };

        document.addEventListener('keydown', handleShortcut);

        return () => document.removeEventListener('keydown', handleShortcut);
    }, []);

    useEffect(() => {
        if (!user) return undefined;

        try {
            if (window.sessionStorage.getItem(BUG_HINT_KEY) === 'true') return undefined;
            window.sessionStorage.setItem(BUG_HINT_KEY, 'true');
        } catch {
            // The hint can still be displayed when session storage is unavailable.
        }

        const showTimer = window.setTimeout(() => setBugHintVisible(true), 1200);
        const hideTimer = window.setTimeout(() => setBugHintVisible(false), 8200);
        return () => {
            window.clearTimeout(showTimer);
            window.clearTimeout(hideTimer);
        };
    }, [user]);

    const closeMobileNavigation = () => {
        if (window.matchMedia('(max-width: 1023px)').matches) {
            setNavigationVisible(false);
        }
    };

    const toggleSection = (slug) => {
        setExpandedSections((current) => (
            current.includes(slug)
                ? current.filter((item) => item !== slug)
                : [...current, slug]
        ));
    };

    const signOut = async () => {
        if (signingOut) {
            return;
        }

        setSigningOut(true);

        try {
            await axios.post('/logout', null, { silent: true });
            window.location.assign(appPath('/login'));
        } catch {
            notify({
                type: 'error',
                title: 'Sign out failed',
                message: 'Your session could not be closed. Refresh the page and try again.',
            });
            setSigningOut(false);
        }
    };

    const switchStation = async (selectedStation) => {
        if (switchingStation || selectedStation === station.current) {
            return;
        }

        setSwitchingStation(true);

        try {
            await axios.put('/api/station', { station: selectedStation }, { silent: true });
            window.location.reload();
        } catch {
            notify({
                type: 'error',
                title: 'Station change failed',
                message: 'The station could not be changed. Refresh the page and try again.',
            });
            setSwitchingStation(false);
        }
    };

    const pageTitle = activeContext.item?.label
        ?? activeContext.section?.label
        ?? (currentPath.startsWith('/dashboard') ? 'Dashboard' : 'Monster Content Management System');

    return (
        <div className="min-h-screen bg-canvas text-ink transition-colors duration-300">
            <CommandPalette navigation={navigation} onClose={() => setSearchOpen(false)} open={searchOpen} />
            <BugReportModal onClose={() => setBugReportOpen(false)} open={bugReportOpen} />
            {isBusy && (
                <div className="fixed inset-x-0 top-0 z-[80] h-1 overflow-hidden bg-rx-blue/20" role="progressbar" aria-label="Loading">
                    <span className="block h-full w-1/3 animate-[loading-bar_1s_ease-in-out_infinite] bg-rx-yellow" />
                </div>
            )}

            <button
                aria-label="Close navigation"
                className={`fixed inset-0 z-40 bg-black/60 transition-all duration-300 lg:hidden ${
                    navigationVisible ? 'visible opacity-100 backdrop-blur-[2px]' : 'invisible opacity-0'
                }`}
                onClick={() => setNavigationVisible(false)}
                type="button"
            />

            <aside
                aria-label="Primary navigation"
                className={`fixed inset-y-0 left-0 z-50 flex w-80 flex-col border-r border-sidebar-line bg-sidebar text-sidebar-ink transition-[background-color,color,border-color,transform] duration-300 ease-out ${
                    navigationVisible ? 'translate-x-0' : '-translate-x-full'
                }`}
            >
                <div className="flex h-20 shrink-0 items-center justify-between px-5">
                    <Brand />
                    <button
                        aria-label="Hide navigation"
                        className="rounded-lg p-2 text-sidebar-ink-muted transition-colors duration-200 hover:bg-sidebar-muted hover:text-rx-blue"
                        onClick={() => setNavigationVisible(false)}
                        type="button"
                    >
                        <Icon className="h-5 w-5" name="close" />
                    </button>
                </div>

                <nav
                    className="flex-1 overflow-y-auto px-3 pb-6"
                    onScroll={(event) => {
                        if (restoringSidebarScrollRef.current) return;
                        try {
                            window.sessionStorage.setItem(SIDEBAR_SCROLL_KEY, String(event.currentTarget.scrollTop));
                        } catch {
                            // Navigation remains usable when browser storage is unavailable.
                        }
                    }}
                    ref={sidebarNavigationRef}
                >
                    <p className="px-3 pb-2 pt-4 font-heading text-[0.65rem] font-semibold uppercase tracking-[0.24em] text-sidebar-ink-muted">
                        Station management
                    </p>
                    <Link
                        className={`flex min-h-11 items-center gap-3 rounded-lg px-3.5 font-heading text-sm font-semibold uppercase tracking-wide transition-all duration-200 ${
                            currentPath.startsWith('/dashboard')
                                ? 'bg-rx-blue text-neutral-950'
                                : 'text-sidebar-ink-muted hover:bg-sidebar-muted hover:text-sidebar-ink'
                        }`}
                        href={appPath('/dashboard')}
                        onClick={closeMobileNavigation}
                    >
                        <Icon className="h-5 w-5 shrink-0" name="dashboard" />
                        Dashboard
                    </Link>

                    <div className="mt-2 space-y-1">
                        {navigation.map((section) => {
                            const expanded = expandedSections.includes(section.slug);
                            const active = activeContext.section?.slug === section.slug;

                            return (
                                <div key={section.slug}>
                                    <button
                                        aria-expanded={expanded}
                                        className={`flex min-h-11 w-full items-center gap-3 rounded-lg px-3.5 text-left font-heading text-sm font-semibold uppercase tracking-wide transition-all duration-200 ${
                                            active
                                                ? 'bg-sidebar-muted text-rx-blue dark:text-rx-yellow'
                                                : 'text-sidebar-ink-muted hover:bg-sidebar-muted hover:text-sidebar-ink'
                                        }`}
                                        onClick={() => toggleSection(section.slug)}
                                        type="button"
                                    >
                                        <Icon className="h-5 w-5 shrink-0" name={section.icon} />
                                        <span className="min-w-0 flex-1 truncate">{section.label}</span>
                                        <Icon className={`h-4 w-4 shrink-0 transition-transform duration-300 ${expanded ? 'rotate-180' : ''}`} name="chevron" />
                                    </button>

                                    <div className={`grid transition-[grid-template-rows,opacity] duration-300 ease-out ${
                                        expanded ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'
                                    }`}>
                                        <div className="overflow-hidden">
                                            <div className="mb-2 ml-5 border-l border-sidebar-line pb-1 pl-4 pt-2">
                                                <Link
                                                    className={`block rounded-md px-3 py-2 text-sm transition-colors duration-200 ${
                                                        currentPath === `/workspace/${section.slug}`
                                                            ? 'bg-sidebar-active text-rx-blue'
                                                            : 'text-sidebar-ink-muted hover:bg-sidebar-muted hover:text-sidebar-ink'
                                                    }`}
                                                    href={appPath(`/workspace/${section.slug}`)}
                                                    onClick={closeMobileNavigation}
                                                >
                                                    Overview
                                                </Link>
                                                {section.groups.map((group) => (
                                                    <div className="pt-3" key={group.label}>
                                                        <p className="px-3 pb-1 text-[0.62rem] font-semibold uppercase tracking-[0.2em] text-sidebar-ink-muted">
                                                            {group.label}
                                                        </p>
                                                        {group.items.map((item) => {
                                                            const relativeHref = `/workspace/${section.slug}/${item.slug}`;
                                                            const href = appPath(relativeHref);

                                                            return (
                                                                <Link
                                                                    className={`block rounded-md px-3 py-2 text-sm transition-colors duration-200 ${
                                                                        currentPath === relativeHref
                                                                            ? 'bg-sidebar-active text-rx-blue'
                                                                            : 'text-sidebar-ink-muted hover:bg-sidebar-muted hover:text-sidebar-ink'
                                                                    }`}
                                                                    href={href}
                                                                    key={item.slug}
                                                                    onClick={closeMobileNavigation}
                                                                >
                                                                    {item.label}
                                                                </Link>
                                                            );
                                                        })}
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </nav>

                <div className="shrink-0 space-y-3 px-5 py-4">
                    <SystemStatus authenticated={Boolean(user)} />
                    {user && (
                        <div className="flex items-center gap-3 rounded-lg bg-sidebar-muted px-3 py-3">
                            <div className="min-w-0 flex-1">
                                <p className="truncate text-xs font-semibold text-sidebar-ink">{user.email}</p>
                                <p className="mt-0.5 text-[0.62rem] uppercase tracking-[0.14em] text-sidebar-ink-muted">Signed in</p>
                            </div>
                            <button
                                aria-label="Sign out"
                                className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sidebar-ink-muted transition-all duration-200 hover:bg-red-500 hover:text-white disabled:cursor-wait disabled:opacity-60"
                                disabled={signingOut}
                                onClick={signOut}
                                title="Sign out"
                                type="button"
                            >
                                <Icon className="h-4 w-4" name="logout" />
                            </button>
                        </div>
                    )}
                </div>
            </aside>

            <header className={`relative z-30 flex h-20 items-center justify-between border-b border-line/50 bg-surface px-4 transition-[margin,background-color,border-color] duration-300 ease-out sm:px-6 lg:px-8 ${
                navigationVisible ? 'lg:ml-80' : ''
            }`}>
                    <div className="flex min-w-0 items-center gap-4">
                        <button
                            aria-expanded={navigationVisible}
                            aria-label={navigationVisible ? 'Hide navigation' : 'Show navigation'}
                            className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-canvas text-ink transition-all duration-200 hover:bg-rx-blue hover:text-neutral-950"
                            onClick={toggleNavigation}
                            type="button"
                        >
                            <Icon name={navigationVisible ? 'close' : 'menu'} />
                        </button>
                        <div className="min-w-0">
                            <p className="truncate font-heading text-sm font-semibold uppercase tracking-wide">{pageTitle}</p>
                            <p className="hidden text-xs text-ink-muted sm:block">Monster Content Management System</p>
                        </div>
                    </div>
                    <div className="flex items-center gap-2">
                        {user && station.can_switch ? (
                            <div className="w-24 sm:w-44">
                                <CardDropdown
                                    buttonClassName="relative flex h-10 w-full items-center rounded-md border border-line bg-canvas px-3 pr-9 text-left text-xs font-semibold uppercase tracking-wide text-ink shadow-none transition-colors hover:border-rx-blue focus:border-rx-blue focus:bg-rx-blue/10 focus:outline-none"
                                    disabled={switchingStation}
                                    id="station-switcher"
                                    onChange={switchStation}
                                    options={station.options}
                                    value={station.current}
                                />
                            </div>
                        ) : user ? (
                            <span className="hidden rounded-sm border border-line bg-canvas px-2.5 py-2 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-ink-muted sm:inline-flex">
                                {station.current}
                            </span>
                        ) : null}
                        {user && (
                            <div className="relative">
                                <button
                                    aria-label="Report a bug"
                                    className="flex h-10 items-center gap-2 rounded-lg bg-canvas px-3 text-ink-muted transition-all duration-200 hover:bg-rx-blue hover:text-neutral-950"
                                    onClick={() => {
                                        setBugHintVisible(false);
                                        setBugReportOpen(true);
                                    }}
                                    type="button"
                                >
                                    <Icon className="h-4 w-4" name="bug" />
                                    <span className="hidden text-xs font-semibold sm:inline">Report bug</span>
                                </button>
                                <div className={`pointer-events-none absolute right-0 top-full z-50 mt-3 w-72 rounded-lg border border-rx-blue/40 bg-surface p-4 text-sm text-ink shadow-2xl transition-all duration-300 ${bugHintVisible ? 'visible translate-y-0 opacity-100' : 'invisible -translate-y-2 opacity-0'}`}>
                                    <p className="font-heading text-xs font-semibold uppercase tracking-wide text-rx-blue">Found something wrong?</p>
                                    <p className="mt-2 leading-5 text-ink-muted">Use Report bug to describe it and paste screenshots directly into the report.</p>
                                </div>
                            </div>
                        )}
                        <button
                            aria-label="Search management tools"
                            className="flex h-10 items-center gap-2 rounded-lg bg-canvas px-3 text-ink-muted transition-all duration-200 hover:bg-surface-muted hover:text-ink sm:min-w-48"
                            onClick={() => setSearchOpen(true)}
                            type="button"
                        >
                            <Icon className="h-4 w-4 shrink-0" name="search" />
                            <span className="hidden flex-1 text-left text-xs sm:block">Search tools</span>
                            <kbd className="hidden rounded bg-surface px-1.5 py-0.5 text-[0.6rem] font-semibold uppercase tracking-wide lg:block">Ctrl K</kbd>
                        </button>
                        <ThemeSwitcher compact />
                    </div>
            </header>

            <div className={`min-h-screen overflow-x-hidden transition-[padding] duration-300 ease-out ${navigationVisible ? 'lg:pl-80' : ''}`}>
                <main className="rx-page" key={url}>{children}</main>
            </div>
        </div>
    );
}
