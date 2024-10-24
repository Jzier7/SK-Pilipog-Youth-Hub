import { api } from 'src/boot/axios'

const forumPostService = {
  async getPosts(params) {
    const response = await api.get('/api/post/retrieve', { params });
    return response;
  },

  async storePost(data) {
    const response = await api.post('/api/post/store', data );
    return response;
  },

  async updatePost(data) {
    const response = await api.patch('/api/post/update', data );
    return response;
  },

  async deletePost(data) {
    const response = await api.delete('/api/post/delete', { data });
    return response;
  },
};

export default forumPostService;
