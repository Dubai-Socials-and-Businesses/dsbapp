// import 'vuetify/_styles.scss'
import 'vuetify/dist/vuetify.min.css'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import * as colors from "vuetify/util/colors";

export default createVuetify({
    components,
    directives,
    theme:{
        defaultTheme:'light',
        themes:{
            light:{
                colors:{
                    primary:'#1B2A41',
                    secondary:colors.blue.darken3,
                    success:colors.green.darken3,
                    charcoal:'#121212',
                    navy:'#1B2A41',
                    teal:'#00bfa6',
                    mint:'#f1fffc',
                }
            }
        }
    },
    typography:{
        fontFamily:'Poppins, Roboto, sans-serif',
        button: 'Poppins, Roboto, sans-serif',
        headline: 'Poppins, Roboto, sans-serif',
        text: 'Poppins, Roboto, sans-serif',
    }
})
