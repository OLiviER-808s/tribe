export const removeDuplicates = (array: any[]) => {
    return array.filter((value, index, self) => self.indexOf(value) === index)
}

export const insertToIndex = (array: any[], index: number, ...items: any) => {
    return array.splice(index, 0, ...items)
}