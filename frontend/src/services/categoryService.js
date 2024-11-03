import { api } from 'src/boot/axios'

const categoryService = {
  async getPaginatedCategories(params = null) {
    const response = await api.get('/api/category/retrieve/paginated', { params });
    return response;
  },

  async getAllCategories() {
    const response = await api.get('/api/category/retrieve/all');
    return response;
  },

  async storeCategory(data) {
    const response = await api.post('/api/category/store', data );
    return response;
  },

  async updateCategory(data) {
    const response = await api.patch('/api/category/update', data );
    return response;
  },

  async deleteCategory(data) {
    const response = await api.delete('/api/category/delete', { data });
    return response;
  },
};

export default categoryService;
