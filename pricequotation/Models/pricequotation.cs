using System.ComponentModel.DataAnnotations;

namespace pricequotation.Models
{
    public class PriceQuotationModel
    {
        [Required(ErrorMessage = "Please enter a subtotal.")]
        [Range(0.01, double.MaxValue, ErrorMessage = "Subtotal must be greater than 0.")]
        public decimal? Subtotal { get; set; }

        [Required(ErrorMessage = "Please enter a discount percent.")]
        [Range(0, 100, ErrorMessage = "Discount percent must be between 0 and 100.")]
        public decimal? DiscountPercent { get; set; }

        public decimal? DiscountAmount { get; set; }
        public decimal? Total { get; set; }

        public void Calculate()
        {
            if (Subtotal.HasValue && DiscountPercent.HasValue)
            {
                DiscountAmount = Subtotal * (DiscountPercent / 100);
                Total = Subtotal - DiscountAmount;
            }
        }
    }
}
