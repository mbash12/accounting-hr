<template>
    <main class="bg-white">
        <div class="w-full h-full flex flex-col">
            <Header title="Approval Izin" />
            <div class="w-full h-full flex flex-col justify-center gap-5 p-5">
              <div class="w-full flex flex-col gap-5 items-center justify-center p-5 rounded-xl h-[90%]" style="box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);">
                <img src="/done.gif" alt=""/>
                <span class="font-bold text-22px">Berhasil Disetujui!</span>
                <!-- <span></span> -->
              </div>
            </div>
            <div class="p-6">
              <Button block @click="$router.push('/home')" variant="red" class="h-60px">Kembali ke Home</Button>
            </div>
        </div>
    </main>
</template>
