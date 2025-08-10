// Navigation Debug Utility
class NavigationDebugger {
    constructor() {
        this.logs = [];
        this.isEnabled = import.meta.env.DEV; // Only enable in development
    }

    log(event, data = {}) {
        if (!this.isEnabled) return;

        const logEntry = {
            timestamp: new Date().toISOString(),
            event,
            data,
            url: window.location.href
        };

        this.logs.push(logEntry);
        console.group(`🔍 Navigation Debug: ${event}`);
        console.log('Timestamp:', logEntry.timestamp);
        console.log('Current URL:', logEntry.url);
        console.log('Data:', data);
        console.groupEnd();

        // Keep only last 50 logs
        if (this.logs.length > 50) {
            this.logs = this.logs.slice(-50);
        }
    }

    getRecentLogs(count = 10) {
        return this.logs.slice(-count);
    }

    clearLogs() {
        this.logs = [];
        console.log('🧹 Navigation debug logs cleared');
    }

    exportLogs() {
        const data = JSON.stringify(this.logs, null, 2);
        const blob = new Blob([data], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `navigation-debug-${Date.now()}.json`;
        a.click();
        URL.revokeObjectURL(url);
    }
}

const navDebugger = new NavigationDebugger();

// Make debugger globally available for console debugging
if (typeof window !== 'undefined') {
    window.navigationDebugger = navDebugger;
}

export default navDebugger;
