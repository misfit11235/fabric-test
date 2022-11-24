<script setup>
import { ref } from 'vue';

let titles = ref(null);
let currentPage = ref(1);
let pages = ref(0);
let term = ref(null);

const getTitle = (title) => {
  term.value = title;
  currentPage.value = 1;

  axios.get('/api/title', { 
    params: {
      title: title
    }})
  .then(response => {
    titles.value = response.data.titles;
    pages.value = response.data.pages;
  });
}

const getNextPage = () => {
  currentPage.value += 1;
  axios.get('/api/title', {
    params: {
      title: term.value,
      page: currentPage.value
    }
  })
  .then(response => {
    titles.value = titles.value.concat(response.data.titles);
  })
}

const getTitlePoster = (title) => {
  if(title.poster.url === 'N/A') {
    return 'background-color: #cacaca;';
  }
  const posterUrl = title.poster.url;
  return `background-image: url("${posterUrl}");`;
}

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

</script>

<template>
  <div id="top" class="">
    <div class="flex flex-col justify-center items-center font-bold text-6xl bg-gradient-to-b from-red-400 to-blue-400 p-4">
      <p class="text-center">Open Movie Database Browser</p>
      <p class="text-xl mb-2">(as of Q4 2022 only for Matrix aficionados)</p>
    </div>
    <div class="my-8 pb-2">
      <div class="flex justify-center">
        <p class="text-center opacity-50 text-sm max-w-prose">This is your last chance. After this, there is no turning back. You take the blue pill - the story ends, you wake up in your bed and believe whatever you want to believe. You take the red pill - you stay in Wonderland and I show you how deep the rabbit-hole goes.</p>
      </div>
      <div class="flex justify-center flex-wrap">
        <button class="px-6 py-4 text-lg rounded-full bg-blue-400 font-bold m-4" @click="getTitle('Matrix')">MATRIX</button>
        <button class="px-4 py-2 border-2 border-gray-300 rounded-full font-bold m-4" @click="getTitle('Matrix Reloaded')">MATRIX RELOADED</button>
        <button class="px-4 py-2 bg-red-400 rounded-full font-bold m-4" @click="getTitle('Matrix Revolutions')">MATRIX REVOLUTIONS</button>
      </div>
      <div class="flex flex-wrap justify-center items-center px-4 m-2">
        <div v-for="(title, index) in titles" :key="index" class="px-2 w-full md:w-1/2 lg:w-1/4 xl:w-1/5 my-8">
          <p class="text-center italic"><span class="">{{ index + 1 }}. {{ title.title }}</span> ({{ title.year }})</p>
          <p class="text-center  text-sm opacity-50 mb-2"> - {{ title.type }} - </p>
          <div class="h-96 w-64 bg-cover bg-center mx-auto" :style="getTitlePoster(title)" :title="`Poster for ${title.title}(${title.year})`"></div>
        </div>
      </div>
      <div v-if="titles" class="flex flex-col justify-center items-center">
          <button v-show="currentPage !== pages" @click="getNextPage" class="bg-black text-white rounded-full font-bold uppercase px-4 py-2">more</button>
          <button v-show="currentPage === pages" @click="scrollToTop" class="bg-black text-white rounded-full font-bold uppercase px-4 py-2">back to top</button>
      </div>
    </div>
  </div>
</template>