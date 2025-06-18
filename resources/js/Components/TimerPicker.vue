<template>
    <div class="d-flex gap-2">
        <select @change="onChangeTime" class="form-select text-center" v-model="form_data.hour">
            <option value="" disabled>Choose Hour</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
        </select>
        <span class="mx-2 fs-5 d-flex align-items-center justify-content-center">:</span>
        <select @change="onChangeTime" class="form-select text-center" v-model="form_data.minute">
            <option value="" disabled>Choose Minute</option>
            <option value="00">00</option>
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
            <option value="60">60</option>
        </select>

        <select @change="onChangeTime" class="form-select" v-model="form_data.am_pm">
            <option value="" disabled>Choose AM/PM</option>
            <option value="am">AM</option>
            <option value="pm">PM</option>
        </select>
    </div>
</template>

<script>
export default {
    name: 'TimerPicker',
    data(){
        return{
            form_data:{
                hour:"",
                minute:"",
                am_pm:""
            }
        }
    },
    methods:{
        onChangeTime(){
            this.$emit('change-time', this.form_data);
        },
        clearTime(){
            this.form_data.hour="";
            this.form_data.minute="";
            this.form_data.am_pm="";
        },
        setTime(hour,minute,am_pm){
            this.form_data.hour=hour;
            this.form_data.minute=minute;
            this.form_data.am_pm=am_pm;
        }
    }
}
</script>
