$(() => {
    class slot {
        constructor() {
            this.time = 0;
            this.setRandNum('box_1');
            this.setRandNum('box_2');
            this.setRandNum('box_3');
        }

        // 数字をランダムで入力する処理
        setRandNum(id) {
            setInterval(function() {
                document.getElementById(id).value = Math.floor(Math.random() * 10);
            }, 50);
        }

        // 数字を止める処理
        stopSlotBox(id) {
            clearInterval(id);
        }
    }
    const Slot = new slot();
    $('.item').on('click',  e => {
        alert(e.target.id);
        Slot.stopSlotBox(e.target.id);
    })
});