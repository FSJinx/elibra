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
    get: 'branch/get',
}

export function useBranch() {
    const store = branchStore()
    const params = reactive<Params>({ ...defaultParams })

    async function getBranches(forced = false) {
        if (!forced && store.branches.length) {
            return store.branches
        }

        store.setLoading(true)

        try {
            const res = await api.get(url.get, {
                params: {
                    ...params,
                },
            })

            const branches = res.data.data.data

            store.setBranches(branches)

            return branches
        } catch (error) {
            console.error('Error fetching branches:', error)
            return []
        } finally {
            store.setLoading(false)
        }
    }

    async function refresh() {
        Object.assign(params, defaultParams)

        return getBranches(true)
    }

    return {
        params,
        getBranches,
        refresh,
    }
}