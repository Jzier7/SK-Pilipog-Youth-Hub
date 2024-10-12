import { api } from 'src/boot/axios'

const categoryService = {
  async getAllCategories(params = null) {
    const response = await api.get('/api/category/retrieve/all', { params });
    return response;
  },
};

export default categoryService;
