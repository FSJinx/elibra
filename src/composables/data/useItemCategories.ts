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

const url = {
    get: 'item-type-categories',
}

export function useItemCategories() {
    const store = itemCategoriesStore()
    const params = reactive<Params>({ ...defaultParams })

    async function getItemCategories(forced = false) {
        if(!forced && store.itemCategories && store.itemCategories.length) {
            return store.itemCategories
        }

        store.setLoading(true)

        try {
            const res = await api.get(url.get, {
                params: {
                    ...params,
                },
            })

            store.setItemCategories(res.data.data)
            return res.data.data
        } catch (error) {
            console.error('Error fetching item categories:', error)
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
            getItemCategories(true)
        },
    )

    return { params, getItemCategories, refresh }
}