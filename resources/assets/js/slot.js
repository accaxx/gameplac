$(() => {
    class slot {
        constructor() {
            this.time = 0;
            this.setRandNum();
        }

        // 数字をランダムで入力する処理
        setRandNum() {
            for (let i = 1; i <= 3; i++) {
                this['box_' + i] = setInterval(function() {
                    document.getElementById('box_' + i).value = Math.floor(Math.random() * 10);
                }, 50);
            }
        }

        // 数字を止める処理
        stopSlotBox(id) {
            clearInterval(this[id]);
            if (Slot.isFinishOrContinue()) {
                Slot.getResultByBoxes();
            }
            return;
        }

        // 終わりか判定する処理
        isFinishOrContinue() {
            if (document.getElementsByClassName('fixed').length == 3) {
                return true;
            }
            return false;
        }

        // 3つのboxから結果を取得する処理
        getResultByBoxes() {
            for (let i = 1; i <= 3; i++) {
                this['result_' + i] = document.getElementById('box_' + i).value;
            }

            if (this['result_1'] == this['result_2'] && this['result_1'] == this['result_3']) {
                alert('当たり!');
                return;
            }
            alert('はずれ');
            return;
        }
    }

    const Slot = new slot();
    $('.item').on('click',  e => {
        e.currentTarget.classList.add('fixed');
        Slot.stopSlotBox(e.target.id);
    })
});