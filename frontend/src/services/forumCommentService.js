import { api } from 'src/boot/axios'

const forumCommentService = {
  async getComments(params) {
    const response = await api.get('/api/comment/retrieve', { params });
    return response;
  },

  async storeComment(data) {
    const response = await api.post('/api/comment/store', data );
    return response;
  },

  async updateComment(data) {
    const response = await api.patch('/api/comment/update', data );
    return response;
  },

  async deleteComment(data) {
    const response = await api.delete('/api/comment/delete', { data });
    return response;
  },
};

export default forumCommentService;
