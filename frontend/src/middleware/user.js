// src/middleware/user.js
import { useUserStore } from 'src/stores/modules/userStore';
import { USER_ROLES } from 'src/utils/constants';

export function user({ next }) {
    const userStore = useUserStore();
    const userRole = userStore.userData.role ? userStore.userData.role.slug : null;

    if (userRole === USER_ROLES.SUPERADMIN) {
        return next({ path: '/superadmin/dashboard' });
    } else if (userRole === USER_ROLES.ADMIN) {
        return next({ path: '/admin/dashboard' });
    } else if (userRole === USER_ROLES.USER) {
        return next();
    } else if (userRole === USER_ROLES.GUEST) {
        return next({ path: '/guest/home' });
    } else {
        return next({ path: '/' });
    }
}

