import { usePopup } from '../../composables/ui/usePopup';
export const useAdmin = () => {
    return {
        type: useItemTypeStore()
    }
}