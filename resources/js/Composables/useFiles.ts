export const useFiles = () => {
    const readableFileSize = (bytes: number) => {
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']

        if (bytes === 0) return '0 Byte'

        const i = Math.floor(Math.log(bytes) / Math.log(1024))
        const size = Math.round(bytes / Math.pow(1024, i))

        return `${size} ${sizes[i]}`
    }

    return { readableFileSize }
}