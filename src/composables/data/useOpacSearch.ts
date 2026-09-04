export const useOpacSearch = () => {
    const loading = ref(false)
    const error = ref<string | null>(null)

    const results = ref<any[]>([]) 
    const total = ref(0)

    const url = {
        get: 'opac/search',
    }

    async function search(params: Record<string, any>) {
        loading.value = true
        error.value = null

        try {
            const res = await api.get(url.get, {
                params, 
            })

            results.value = res.data?.data?.results?.data ?? []
            total.value = res.data?.data?.results?.total ?? 0

            // return res.data

        } catch (err: any) {
            error.value = err?.data.message ?? 'Unable to search catalog.'

            results.value = []
            total.value = 0

            throw err
        } finally {
            loading.value = false
        }
    }

    return {
        total,
        loading,
        error,
        results,
        search,
    } 
}