import { version } from '../../../package.json';

export default function AppVersion({ className = '' }) {
    return (
        <p className={`text-center text-xs ${className}`}>
            Version {version}
        </p>
    );
}
