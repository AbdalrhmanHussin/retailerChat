import axios from "axios";

export default {
    methods: {
        dateFormate({todayDate = false,ReqDate = '',reserveDistance=false,formate={day: true,month:true,year:true,hour:true,min:true}}) 
        {
            if(!todayDate && ReqDate.length == 0) throw 'Provide a date to formate'
            //today date
            let today   = new Date();
            let tMonth  = today.getMonth();
            let tYear   = today.getFullYear();
            let tDay    = today.getDate();

            let dateReq = new Date(ReqDate);
            let days    = ['Sun','Mon','Tus','Wen','Thu','Fri','Sat'];
            let monthes = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            let day     = dateReq.getDate();
            let month   = dateReq.getMonth();
            let year    = dateReq.getFullYear();
            let period;


            
            let $date;

            if(todayDate)
            {
                $date =  `${tDay + '/' + tMonth + '/' + tYear}`
            } 

            else 
            {
                let rMonth = undefined;
                let rYear  = undefined;
                let rDay   = undefined;
                let wDay   = dateReq.getDay();
                let rHour;
                let rMin;
                let period = (dateReq.getHours() < 12) ? 'am' : 'pm';

                
                if(formate.hour)
                {
                    rHour = dateReq.getHours();
                    rHour = (rHour > 10) ? rHour - 10: rHour;
                    rHour = (rHour < 10) ? '0'+rHour : rHour;
                }

                if(formate.min)
                {
                    rMin  = dateReq.getMinutes();
                    rMin = (rMin < 10) ? '0'+rMin : rMin;
                }

                if(formate.day)
                {
                    if(tDay !== day && !reserveDistance  && tMonth !== month) {
                        rDay = day;    
                    }
                }

                if(formate.month)
                {
                    if(tMonth !== month && !reserveDistance && tDay !== day) {
                        rMonth = month;
                    }
                }

                if(formate.year)
                {
                    if( !reserveDistance && tYear !== year) {
                        rYear = year;
                    }   
                }
                return {
                    today: today,
                    hour: rHour,
                    min: rMin,
                    day: rDay,
                    dayLet: days[wDay],
                    month: rMonth,
                    monthLet: monthes[rMonth],
                    year: rYear,
                    period: period
                }
            }

            return $date;
        },

        textRender(text = text,classList = []) 
        {
            let textToArray = text.split(' ');
            let exportedStr = '';
            classList = classList.join(' ')

            textToArray.forEach(text => {
                if(new RegExp("([a-zA-Z0-9]+://)?([a-zA-Z0-9_]+:[a-zA-Z0-9_]+@)?([a-zA-Z0-9.-]+\\.[A-Za-z]{2,4})(:[0-9]+)?(/.*)?").test(text)) {
                    exportedStr += ` <a href="${text}" class="${classList}">${text}</a>`;
                } else {
                    exportedStr += ' '+text;
                }
            });

            return exportedStr;

        }
    }
}
