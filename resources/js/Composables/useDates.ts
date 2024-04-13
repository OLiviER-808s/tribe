export const useDates = () => {
    const formatTimestamp24Hour = (timestamp: Date) => {
        const userLocale = navigator.language || 'en-US'
        const userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone
    
        const formatter = new Intl.DateTimeFormat(userLocale, {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            timeZone: userTimeZone
        })
    
        return formatter.format(timestamp)
    }
    
    const formatDate = (timestamp: Date) => {
        const userLocale = navigator.language || 'en-US'
        const userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone
    
        const formatter = new Intl.DateTimeFormat(userLocale, {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            timeZone: userTimeZone
        })
    
        return formatter.format(timestamp)
    }
    
    const differentDay = (date1: Date, date2: Date) => {
        return (
            date1.getFullYear() !== date2.getFullYear() ||
            date1.getMonth() !== date2.getMonth() ||
            date1.getDate() !== date2.getDate()
        )
    }

    return { formatTimestamp24Hour, formatDate, differentDay }
}