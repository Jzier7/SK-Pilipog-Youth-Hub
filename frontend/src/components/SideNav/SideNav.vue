<template>
  <q-drawer
    :model-value="drawerOpen"
    show-if-above
    :width="300"
    :breakpoint="700"
    bordered
    @update:model-value="$emit('update:drawerOpen', $event)"
  >
    <q-scroll-area class="fit">
      <div class="flex justify-center items-center py-4">
        <img src="~/assets/logo.png" alt="Logo" class="h-auto w-3/4 py-4" />
        <h3 class="text-primary">{{ userName }}</h3>
      </div>

      <q-separator inset />

      <q-list>
        <template v-for="(menuItem, index) in menuList" :key="index">
          <q-item
            clickable
            v-ripple
            @click="navigateTo(menuItem.path)"
            :active="$route.path === menuItem.path"
            :class="[$route.path === menuItem.path ? 'bg-secondary text-white' : 'text-primary', 'mx-2 rounded-lg']"
          >
            <q-item-section avatar>
              <q-icon :name="menuItem.icon" />
            </q-item-section>
            <q-item-section> {{ menuItem.label }} </q-item-section>
          </q-item>

          <q-separator :key="'sep' + index" inset v-if="menuItem.separator" />
        </template>
      </q-list>
    </q-scroll-area>
  </q-drawer>
</template>

<script>
import { useUserStore } from 'src/stores/modules/userStore';

export default {
  name: 'SideNav',
  props: {
    drawerOpen: {
      type: Boolean,
      required: true
    },
    menuList: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      userName: '',
    }
  },
  methods: {
    navigateTo(path) {
      this.$router.push(path);
    }
  },
  created() {
    const userStore = useUserStore();
    userStore.fetchUser().then(() => {
      this.userName = userStore.userData.first_name + ' ' + userStore.userData.last_name;
    });
  },
}
</script>

<style lang="scss" scoped>
.q-drawer {
  height: 100vh;
}
</style>

