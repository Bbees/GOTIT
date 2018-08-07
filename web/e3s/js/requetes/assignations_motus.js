/* **************************
 *  Document ready
 **************************** */
$(document).ready(_ => {
  let pageHandler = new AssignMotu("#main-form", "#result-table", "#details-table")
})



class AssignMotu {
  constructor(formId, tableId, detailsTableId) {
    this.form = $(formId)
    this.table = $(tableId)
    this.details = $(detailsTableId)
    this.lastQuery = {}
    this.detailsFormData = []
    this.seqTypes = {
      interne: this.details.data('vocabSeqInt'),
      externe: this.details.data('vocabSeqExt'),
    }

    this.uiWaitResponse()
    this.speciesSelector = new SpeciesSelector(formId, "#taxa-filter")
    this.methodSelector = new MethodSelector(formId, 'checkbox')

    $.when(this.speciesSelector.promise, this.methodSelector.promise)
      .done(_ => {
        this.initDataTable()
      })
  }

  /**
   * Initialise datatable en lien avec le formulaire et les éléments du DOM
   * 
   */
  initDataTable() {
    let self = this
    if (!$.fn.DataTable.isDataTable("#" + self.table.attr('id'))) {
      const urls = {
        refTaxon: self.table.find("th#col-taxname").data('linkUrl'),
      }
      self.dataTable = self.table.DataTable({
        autoWidth: false,
        responsive: true,
        ajax: {
          "url": self.form.data("url"),
          "dataSrc": "rows",
          "type": "POST",
          "data": _ => {
            return self.form.serialize()
          }
        },
        language: dtconfig.language[self.table.data('locale')],
        dom: "lfrtipB",
        buttons: dtconfig.buttons,
        columns: [{
            data: "taxname",
            render: linkify(urls.refTaxon, 'id', true)
          },
          {
            data: "methode"
          },
          {
            data: "libelle_motu",
          },
          {
            data: "nb_seq"
          },
          {
            data: "nb_motus"
          },
          {
            data: "id",
            render: (data, type, row) => {
              var template = $("#details-form-template").html();
              return Mustache.render(template, row);
            }
          }
        ],
        drawCallback: _ => {
          self.uiReceivedResponse()
          $('[data-toggle="tooltip"]').tooltip()
          $(".details-form").on('submit', event => {
            event.preventDefault();
            self.detailsFormData = $(event.target).serializeArray()
            self.detailsDataTable.ajax.reload()
            $("#modal-container .modal").modal('show');
          });
        }
      }).on('xhr', _ => {
        let response = self.dataTable.ajax.json()
        self.lastQuery = response.query
        if (!$.fn.DataTable.isDataTable("#" + self.details.attr('id'))) {
          self.initModalTable()
        }
      })

      self.form.submit(event => {
        event.preventDefault()
        self.uiWaitResponse()
        self.dataTable.ajax.reload()
      });
    }
  }


  get ajaxData() {
    let self = this
    let data = self.detailsFormData
    self.lastQuery.criteres.forEach(crit => {
      data.push({
        name: 'criteres[]',
        value: crit
      })
    })
    data.push({
      name: 'niveau',
      value: self.lastQuery.niveau
    });
    return data
  }

  /**
   * Initialize datatable on modal table
   * 
   * @param {string} tableId ID for table element in DOM
   */
  initModalTable() {
    let self = this
    self.detailsDataTable = self.details.DataTable({
      autoWidth: false,
      responsive: true,
      ajax: {
        type: 'POST',
        url: self.details.data('url'),
        dataSrc: 'rows',
        data: _ => {
          return self.ajaxData
        }
      },
      language: dtconfig.language[self.details.data('locale')],
      columns: [{
        data: 'code',
        render: (data, type, row) => {
          let lookUpAttr = row.type ? 'urlExt' : 'urlInt'
          let baseUrl = self.details.find("#col-code-seq").data(lookUpAttr)
          return linkify(baseUrl, 'id', true, 'right')(data, type, row)
        }
      }, {
        data: 'acc',
        render: linkify('https://www.ncbi.nlm.nih.gov/nuccore/', 'acc', false)
      }, {
        data: 'gene'
      }, {
        data: 'type',
        render: data => {
          return data ? self.seqTypes.externe : self.seqTypes.interne
        }
      }, {
        data: 'motu'
      }, {
        data: 'critere'
      }],
      dom: "lfrtipB",
      buttons: dtconfig.buttons,
      drawCallback: _ => {
        $('[data-toggle="tooltip"]').tooltip()
      }
    })
  }


  /**
   * Active le mode attente / loading
   */
  uiWaitResponse() {
    this.form.find("button[type='submit']").button('loading')
  }

  /**
   * Désactive le mode attente 
   */
  uiReceivedResponse() {
    this.form.find("button[type='submit']").button('reset')
  }

} // class AssignMotu