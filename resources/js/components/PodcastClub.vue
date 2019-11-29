<template>

  <div class="club-container">

    <h3 style="margin-bottom:20px">{{ club.name }}</h3>

    <ul class="list-group">

        <li v-for="(podcast, index) in club.podcasts" v-bind:podcast="podcast" v-bind:key="podcast.id" class="list-group-item d-flex justify-content-between align-items-center">
          <span class="overflow-preventer">
            <img :src="podcast.artwork" class="artwork" alt="..." />
            <span class="podcast-description"><span class="podcast-order">{{ index + 1 }}</span> {{ podcast.description }}</span>
          </span>
          <span class="badge"><a href="#" v-on:click="test(index)"><img src="/images/icon-more.svg" class="podcast-more" /></a></span>
        </li>
    </ul>


    </div>

</template>

<script>
module.exports = {
  data: function() {
    return {
    }
  },
  props: [
    'club'
  ],
  methods: {
    test:function(index) {
      Vue.delete(this.club.podcasts, index);
    }
  }
}
</script>
