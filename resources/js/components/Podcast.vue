<template>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <span class="overflow-preventer">
      <img :src="podcast.artwork" class="artwork" alt="..." />
      <span class="podcast-description"><span class="podcast-order">{{ podcast.order }}</span> {{ podcast.description }}</span>
    </span>
    <span class="badge"><a href="#" v-on:click="test(podcast)"><img src="/images/icon-more.svg" class="podcast-more" /></a></span>
  </li>
</template>

<script>
module.exports = {
  data: function() {
    return {
    }
  },
  props: [
    'podcast'
  ],
  methods: {
    test: function (event) {
      console.log(event);
    }
  }
}
</script>
