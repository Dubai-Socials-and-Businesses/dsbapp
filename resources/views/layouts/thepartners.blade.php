<section class="bg-teal py-5">
    <div class="container">
        <h2 class="fw-bold text-navy display-4" data-aos="fade-right">The Partners</h2>
        <p class="text-dark" data-aos="fade-right">
            Where brands meet the right audience — face to face. Our meetups attract Dubai’s most
            ambitious professionals and SME founders. Partnering with DSB means your brand
            doesn’t just get a logo spot — it gets conversations, visibility, and leads
        </p>
        <h3 class="text-navy" data-aos="fade-right"> Choose your spotlight:</h3>
        <div class="d-flex gap-2" data-aos="fade-right">
            <v-btn color="navy" variant="elevated">Bronze</v-btn>
            <v-btn color="navy" variant="elevated">Silver</v-btn>
            <v-btn color="navy" variant="elevated">Gold</v-btn>
            <v-btn color="navy" variant="elevated">Platinum</v-btn>
        </div>
        <script>
            document.addEventListener( 'DOMContentLoaded', function () {
                new Splide( '#image-carousel', {
                    perPage    : 4,
                    gap:'1rem',
                    type    : 'loop',
                    autoplay: 'play',
                    perMove:1,
                    breakpoints: {
                        640: {
                            perPage: 1,
                        },
                    },
                } ).mount();
            } );
        </script>
        <section id="image-carousel" class="splide mt-5" aria-label="Beautiful Images">
            <div class="splide__track">
                <ul class="splide__list">
                    <li class="splide__slide">
                        <img src="{{asset('/mind1a.jpg')}}" class="img-fluid" alt="">
                    </li>
                    <li class="splide__slide">
                        <img src="{{asset('/mind1b.jpg')}}" class="img-fluid" alt="">
                    </li>
                    <li class="splide__slide">
                        <img src="{{asset('/mind1c.jpg')}}" class="img-fluid" alt="">
                    </li>
                    <li class="splide__slide">
                        <img src="{{asset('/mind1b.jpg')}}" class="img-fluid" alt="">
                    </li>
                    <li class="splide__slide">
                        <img src="{{asset('/mind1c.jpg')}}" class="img-fluid" alt="">
                    </li>
                    <li class="splide__slide">
                        <img src="{{asset('/mind1b.jpg')}}" class="img-fluid" alt="">
                    </li>
                    <li class="splide__slide">
                        <img src="{{asset('/mind1c.jpg')}}" class="img-fluid" alt="">
                    </li>
                </ul>
            </div>
        </section>
{{--        <Carousel mt="xl" withIndicators={true} withControls={false} slideGap="md"--}}
{{--                  slideSize={{ base: '100%', sm: '70%', md: '25%' }}--}}
{{--                          emblaOptions={{ loop: true, align: 'start', slidesToScroll: 1 }}>--}}
{{--            {fbuses.length > 0 && (--}}
{{--            fbuses.map((fbus,idx) => {--}}
{{--            return (--}}
{{--            <CarouselSlide key={idx} my="xs">--}}
{{--                <Card bg="vlblue" withBorder shadow="xs" h="100%">--}}
{{--                    <Flex my="md" gap="md" align="center">--}}
{{--                        <Avatar size={100} src={fbus.img} alt={fbus.name}/>--}}
{{--                            <Box>--}}
{{--                                <Title order={3} fz="h5" c="dblue">{fbus.name}</Title>--}}
{{--                                <PureHtml html={fbus?.review} />--}}
{{--                            </Box>--}}
{{--                    </Flex>--}}
{{--                </Card>--}}
{{--            </CarouselSlide>--}}
{{--            )--}}
{{--            })--}}
{{--            )}--}}
{{--        </Carousel>--}}
        <v-btn class="fw-bold mt-3" variant="outlined" color="navy" data-aos="fade-right">Collaborate With Us</v-btn>
    </div>
</section>
