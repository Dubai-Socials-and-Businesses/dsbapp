<section class="py-5">
    <div class="container">
        <h2 class="fw-bold text-navy display-4" data-aos="fade-right">Our Latest Reviews</h2>
        <p class="text-dark" data-aos="fade-right">
            Our community members in Dubai have shared their thoughts, and the feedback has been overwhelmingly
            positive. Many praised the events for being well-organized, inclusive, and impactful, highlighting how they
            brought people from different backgrounds together to connect and contribute. Participants noted that the
            atmosphere was welcoming, the volunteers supportive, and the activities meaningful, creating a real sense of
            unity. While one or two suggested improvements in coordination, most agreed the initiatives exceeded
            expectations. Overall, these reviews reflect a growing appreciation for social platforms that inspire
            collaboration and strengthen community bonds across Dubai.
        </p>
        <script>
            document.addEventListener( 'DOMContentLoaded', function () {
                new Splide( '#tcarousel', {
                    perPage    : 3,
                    gap:'1rem',
                    breakpoints: {
                        640: {
                            perPage: 1,
                        },
                    },
                } ).mount();
            } );
        </script>
        <section id="tcarousel" class="splide mt-5" aria-label="Beautiful Images">
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
    </div>
</section>
