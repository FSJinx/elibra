interface Acquisition {
    id: number
    purchase_id: string
    dealer: string
    acquisition_mode: string
    acquisition_date: string
    remarks: string
}

export const acqusitionStore  = defineStore('librarian.acquisition', () => {
    const acquisitions = ref<Acquisition[]>()
    const loading = ref<boolean>()

    function setAcquisitions(data: Acquisition[]) {
        acquisitions.value = data
    }

    return {
        acquisitions,
        loading,

        setAcquisitions
    }
})