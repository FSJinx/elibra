interface Params {
    sort: string
    page: number
    per_page: number
    order: 'asc' | 'desc'
}

const defaultParams: Readonly<Params> = {
    page: 1,
    per_page: 10,
    order: 'asc',
    sort: '',
}

// ========== Api Route ==========
const url = {
    get: 'item-types',
}
export function useItemTypes() {
     const store = itemTypeStore()
     const params = reactive<Params>({ ...defaultParams })

     // --------- FETCH ITEM TYPES ---------
     async function getItemTypes(forced = false) {
        if(!forced && store.itemTypes && store.itemTypes.length) {
            return store.itemTypes
        }
    
        store.setLoading(true)

        try {
            const res = await api.get(url.get, {
                params: {
                    ...params,
                },
            })

            store.setItemTypes(res.data.data)
                
            return res.data.data
        } catch (error) {
            console.error('Error fetching item types:', error)
        } finally {
            store.setLoading(false)
        }
    }

    async function refresh() {
        Object.assign(params, defaultParams)
    }

    watchDebounced(
        () => ({ ...params }),
        (p) => {
            getItemTypes(true)
        },
    )
    return { params, getItemTypes, refresh }
}