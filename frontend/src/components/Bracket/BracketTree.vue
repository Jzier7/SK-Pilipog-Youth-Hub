<template>
  <div class="wrapper">
    <TreeNode :node="treeData" />
  </div>
</template>

<script>
import TreeNode from './TreeNode.vue';

export default {
  name: 'BracketTree',
  components: { TreeNode },
  data() {
    return {
      teams: ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6', 'Team 7', 'Team 8'],
      treeData: this.generateBracket(['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6', 'Team 7', 'Team 8'])
    };
  },
  methods: {
    generateBracket(teams) {
      if (teams.length === 0) return null;

      const buildTree = (nodes) => {
        if (nodes.length === 1) {
          return {
            id: nodes[0].id,
            label: nodes[0].label,
            score: nodes[0].score
          };
        }

        const mid = Math.ceil(nodes.length / 2);
        const left = buildTree(nodes.slice(0, mid));
        const right = buildTree(nodes.slice(mid));

        return {
          id: '',
          label: '',
          children: [left, right]
        };
      };

      const formattedTeams = teams.map((team, index) => ({
        id: index.toString(),
        label: team,
        score: 0
      }));

      return buildTree(formattedTeams);
    }
  }
};
</script>

<style lang="scss" scoped>
.wrapper {
  display: flex;
  justify-content: center;
  overflow-x: auto;
  padding: 20px;
  box-sizing: border-box;
  max-width: 100%;
}
</style>

