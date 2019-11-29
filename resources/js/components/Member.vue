<template>

<div>



      <div v-for="(club, clubIndex) in user.clubs" v-bind:club="club" v-bind:key="club.id" class="club-container">

        <div class="club-header row">
          <div class="col-9">
            <h3 style="margin-bottom:20px">{{ club.name }}</h3>
          </div>
          <div class="col-3 text-right">
            <button type="button" class="btn btn-outline-primary btn-sm" @click="editing = !editing" v-if="!editing">Edit</button>
            <button type="button" class="btn btn-success btn-sm" @click="editing = !editing" v-if="editing">Done</button>
          </div>
        </div>

        <ul class="list-group">

        <li v-if="club.podcasts == 0" class="list-group-item d-flex justify-content-between align-items-center disabled">You have no podcasts in the queue for this club. Text me one!</li>
        <transition-group name="list" tag="p">
            <li v-for="(podcast, index) in club.podcasts" v-bind:podcast="podcast" v-bind:key="podcast.id" class="list-group-item d-flex justify-content-between align-items-center">

              <span class="overflow-preventer">
                <img :src="podcast.artwork" class="artwork" alt="..." />
                <span class="podcast-description"><span class="podcast-order">{{ index + 1 }}</span> {{ podcast.description }}</span>
              </span>

              <span class="badge" v-if="editing">
                <a href="#" v-on:click="moveUp(clubIndex,index)"><img src="/images/icon-up.svg" class="podcast-more" v-bind:class="{ disabled: (index == 0 ) }" /></a>
                <a href="#" v-on:click="moveDown(clubIndex,index)"><img src="/images/icon-down.svg" class="podcast-more" v-bind:class="{ disabled: (index == ( club.podcasts.length - 1 ) ) }" /></a>
                <a href="#" v-on:click="deletePodcast(clubIndex,index)"><img src="/images/icon-trash.svg" class="podcast-more" /></a>
              </span>

            </li>
          </transition-group>

        </ul>

      </div>

  </div>

</template>

<script>
module.exports = {
  data: function() {
    return {
      user:{clubs:[]},
      editing: false,
      memberHash: ''
    }
  },
  mounted() {

    var url = window.location.pathname;
    this.memberHash = url.substring(url.lastIndexOf('/')+1);

    axios.post('/api/member-data', {memberHash:this.memberHash})
      .then(response => {
        console.log(response);
        this.user = response.data;
      })
  },
  methods: {
    moveUp:function(clubIndex, index) {
      this.user.clubs[clubIndex].podcasts.move(index,index-1);

      axios.post('/api/update-podcast-order',this.user.clubs[clubIndex])
        .then(response => {
          console.log(response);
        });


    },
    moveDown:function(clubIndex, index) {
      this.user.clubs[clubIndex].podcasts.move(index,index+1);

      axios.post('/api/update-podcast-order',this.user.clubs[clubIndex])
        .then(response => {
          console.log(response);
        });
    },
    deletePodcast:function(clubIndex, index) {

      var shouldDelete = confirm( "Are you sure you want to remove this Podcast?");
      console.log(this.user.clubs[clubIndex].podcasts[index]);
      if (shouldDelete){
        axios.post('/api/delete-podcast',this.user.clubs[clubIndex].podcasts[index])
          .then(response => {
            console.log(response);
          });

          Vue.delete(this.user.clubs[clubIndex].podcasts, index);

      }
    },

  }
}
</script>
