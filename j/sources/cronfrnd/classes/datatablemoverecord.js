/***
 * Содержит общую для списков работ админки статей и портфолио логику перемещения записей таблицы
 * с одной страницы на другую
*/
class DataTableMoveRecord {
	constructor(sTableId, sUrl, context) {
		/** @property context Ссылка на объект приложения. Неолбходимы его методы alert, defaultFailSendFormListener */
		this.context = context;
		/** @property {String} sTableId  - идентификатор таблицы DataTables */
		this.sTableId = sTableId;
		/** @property {String} sUrl  на который должен отправитьбся запрос */
		this.sUrl = sUrl;
	}

	/**
	 * @description Модифицирует html ячеёки таблицы с кнопками управления, добавляя в него кнопку Вверх или Вниз при необходимости.
	 * "Вверх" имеет смысл "Переместить запись на предыдущую страницу"
	 * "Вниз" имеет смысл "Переместить запись на следующую страницу"
	 * Вызывать в функции DataTables render
	 * @param {String} sHtml - ячейки с контролами (там обычно уже есть кнопка "Редактировать" и "Удалить")
	 * @param {Number} nRow порядковый номер строки таблицы начиная с нуля в пределах одной страницы (то есть для первой записи шестой страницы nRow == 0).
	 *  DataTables Config columns[N].render : function (data, type, row, meta)... nRow is meta.row
	 * @param {Object} oSettings DataTables Config columns[N].render : function (data, type, row, meta)... oSettings is meta.settings
	 * @param {Number} nId related dataTables object sql table record id
	 * @return String updated html for render
	*/
	setHtml(sHtml, nRow, oSettings, nId) {
		if (nRow == 0) {
			sHtml += `
			<div class="form-group d-md-inline d-block ">
				<button data-id="${nId}" type="button" class="j-up-btn mt-2 btn btn-primary">
					<i data-id="${nId}" class="fas fa-arrow-up fa-sm"></i>
				</button>
			</div>`;
		}
		if (nRow == (oSettings._iDisplayLength - 1)) {
			sHtml += `
			<div class="form-group d-md-inline d-block ">
				<button data-id="${nId}" type="button" class="j-down-btn mt-2 btn btn-primary">
					<i data-id="${nId}" class="fas fa-arrow-down fa-sm"></i>
				</button>
			</div>`;
		}
		return sHtml;
	}

	setListeners() {
		$(this.sTableId + ' .j-up-btn').click((evt) => {
			this.onClickUpRecord(evt);
		});
		$(this.sTableId + ' .j-down-btn').click((evt) => {
			this.onClickDownRecord(evt);
		});
	}
	/**
	 * @description Обработка клика на кнопке переноса статьи на предыдущую страницу
	 * @param {Object} data 
	*/
	onClickUpRecord(evt) {
		this.sendMoveRecordToPageRequest($(evt.target).attr('data-id'), 'u');
	}
	/**
	 * @description Обработка клика на кнопке переноса статьи на следующую страницу
	 * @param {Object} data 
	*/
	onClickDownRecord(evt) {
		this.sendMoveRecordToPageRequest($(evt.target).attr('data-id'), 'd');
	}
	/**
	 * @description Обработка успешного переноса статьи на новую или предыдущую страницу
	 * @param {Object} data 
	*/
	onSuccessMoveRecord(data) {
		if (!this.onFailMoveRecord(data)) {
			return;
		}
		let s = ('button[data-id=' + data.srcId + ']'), 
			jButton = $(s).first(),
			jRow, ls, i, jCell;
		if (jButton[0]) {
			//Get Table row
			jRow = jButton.parents('tr').first();
			//Set buttons id attribute
			ls = jRow.find('button,i');
			for (i = 0; i < ls.length; i++) {
				if (ls[i].hasAttribute('data-id')) {
					ls[i].setAttribute('data-id', data.newRec.id);
				}
			}
			//Set table row content
			jCell = jRow.find('td')[1];
			if (jCell) {
				jCell = $(jCell);
				if (data.newRec.name) {
					jCell.html('');
					jCell.append( $('<span>' + data.newRec.name + '</span>') );
				} else {
					jCell.text(data.newRec.heading);
				}
				
			}
		}
	}
	/**
	 * @description Обработка неуспешного переноса статьи на новую или предыдущую страницу
	 * @param {Object} data 
	*/
	onFailMoveRecord(data, b, c) {
		this.bIsMoveRecordRequestSended = 0;
		//Hide loader
		$('#spin' + this.bIsMoveRecordRequestSendedRecId).toggleClass('d-none');

		if (data.srcId && !data.newRec) {
			this.context.alert(data.msg);
			return false;
		}
		
		return this.context.defaultFailSendFormListener(data, b, c);
	}
	/**
	 * @description Отправка запроса на перемещение записи на другую страницу
	 * @param {Number} recId
	 * @param {String} direction 'u' - up, 'd' - down 
	*/
	sendMoveRecordToPageRequest(recId, direction) {
		if (!this.bIsMoveRecordRequestSended) {
			let id = recId;
			$('#spin' + id).toggleClass('d-none');
			this.bIsMoveRecordRequestSended = 1;
			this.bIsMoveRecordRequestSendedRecId = id;
			Rest._post({id:id, 'd':direction}, (data) => { this.onSuccessMoveRecord(data) }, this.sUrl, (a, b, c) => { this.onFailMoveRecord(a, b, c); });
		}
	}
}

export default DataTableMoveRecord;