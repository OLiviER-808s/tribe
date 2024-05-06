export const removeDuplicates = (array: any[]) => {
    return array.filter((value, index, self) => self.indexOf(value) === index)
}